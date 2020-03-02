<?php

namespace frontend\components\data\packets;

use Yii;
use yii\base\Component;
use frontend\models\Jlog;
use frontend\services\parser\CParser;

/**
 * Init packages parser into json format
 */
class Init extends Base {

    /**
     * Gets  last init packages json format by their incoming date
     * 
     * @param int $linesNumber
     * 
     * @return array
     */
    public function getLastLinesAsJson(int $linesNumber): array
    {
        $lines = Jlog::find()->select(['packet', 'unix_time_offset'])
                    ->andWhere(['type_packet' => Jlog::TYPE_PACKET_INITIALIZATION])
                    ->orderBy(['unix_time_offset' => SORT_DESC])
                    ->limit($linesNumber)
                    ->asArray()
                    ->all();
        $jsonLines = [];
        $parser = new CParser();

        foreach ($lines as $line) {
            $jsonLines[$line['unix_time_offset']] = $parser->convertInitPacketFromStringToJson($line['packet']);
        }

        return $jsonLines;
    }
}