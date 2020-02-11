<?php

namespace frontend\components\data\packets;

use Yii;
use yii\base\Component;
use frontend\models\CbEncashment;
use frontend\models\CbLogSearch;
use frontend\services\parser\CParser;
use api\modules\v2d00\controllers\JsonController;

/**
 * Encashment packages parser into json format
 */
class Encashment extends Base {

    /**
     * Gets  last encashment packages json format by their incoming date
     * 
     * @param int $linesNumber
     * 
     * @return array
     */
    public function getLastLinesAsJson(int $linesNumber): array
    {
        $lines = CbEncashment::find()->select(
            ['imei', 'status', 'collection_counter', 'fireproof_counter_hrn', 'last_collection_counter', 'notes_billiards_pcs', 'banknote_face_values', 'amount_of_coins', 'coin_face_values', 'unix_time_offset', 'created_at'])
            ->orderBy(['unix_time_offset' => SORT_DESC])
            ->limit($linesNumber)
            ->asArray()
            ->all();
        $jsonLines = [];
        $parser = new CParser();

        foreach ($lines as $line) {
            $jsonLines[$line['created_at']] = $this->convertDataToJson($line);
        }

        return $jsonLines;
    }

    /**
     * Converts encashment package into json format
     * 
     * @param array $line
     * 
     * @return string
     */
    public function convertDataToJson(array $line): string
    {
        $searchModel = new CbLogSearch();
        $jsonData = [
            'imei' => $line['imei'],
            'type' => JsonController::ENCASHMENT,
            'pac' => [
                'time' => $line['unix_time_offset'],
                'totalCash' => $line['fireproof_counter_hrn'],
                'collection' => $line['collection_counter'],
                'collection_last' => $line['last_collection_counter'],
                'notes' => $this->makeItemsByParcedFaceValues($searchModel->parseBanknoteFaceValues($line)),
                'coins' => $this->makeItemsByParcedFaceValues($searchModel->parseCoinFaceValues($line))
            ]
        ];

        return json_encode($jsonData);
    }

    /**
     * Transforms parced faced values into json notes
     * 
     * @param array $faceValues
     * 
     * @return array
     */
    public function makeItemsByParcedFaceValues(array $faceValues): array
    {
        $notes = [];
        $number = 0;
        foreach ($faceValues as $faceValue) {
            switch ($faceValue['nominal']) {
                case "1":
                    $notes['one'] = $faceValue['value'];
                    $number += $faceValue['value'];
                    break;
                case "2":
                    $notes['two'] = $faceValue['value'];
                    $number += $faceValue['value'];
                    break;
                case "5":
                    $notes['five'] = $faceValue['value'];
                    $number += $faceValue['value'];
                    break;
                case "10":
                    $notes['ten'] = $faceValue['value'];
                    $number += $faceValue['value'];
                    break;
                case "20":
                    $notes['twenty'] = $faceValue['value'];
                    $number += $faceValue['value'];
                    break;
                case "50":
                    $notes['fifty'] = $faceValue['value'];
                    $number += $faceValue['value'];
                    break;
            }
        }

        $notes['number'] = $number;

        return $notes;
    }
}