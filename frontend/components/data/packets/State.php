<?php

namespace frontend\components\data\packets;

use Yii;
use yii\base\Component;
use frontend\models\Jlog;
use frontend\services\parser\CParser;

/**
 * State packages parser into json format
 */
class State extends Base {

    /**
     * Gets  last state packages json format by their incoming date
     * 
     * @param int $linesNumber
     * 
     * @return array
     */
    public function getLastLinesAsJson(int $linesNumber): array
    {
        $searchString = "_WM*";
        $searchStringLength = strlen($searchString);
        $lines = Jlog::find()->select(['packet', 'unix_time_offset'])
                    ->andWhere(['type_packet' => Jlog::TYPE_PACKET_DATA])
                    ->andWhere([
                        ">=",
                        "LOCATE(
                            '{$searchString}', SUBSTRING(packet, 1)
                        )",
                        1
                    ])
                    ->orderBy(['unix_time_offset' => SORT_DESC])
                    ->limit($linesNumber)
                    ->asArray()
                    ->all();
        $jsonLines = [];
        $parser = new CParser();

        foreach ($lines as $line) {
            $jsonLines[$line['unix_time_offset']] = $parser->convertDataPacketFromStringToJson($line['packet']);
        }

        return $jsonLines;
    }
}