<?php

namespace frontend\components\data\packets;

use Yii;
use yii\base\Component;
use frontend\services\parser\CParser;
use api\modules\v2d00\controllers\JsonController;
use api\modules\v2d00\UseCase\Log\Log;

/**
 * Central Board logs parser into json format
 */
class CbLog extends Base {

    /**
     * Gets  last log packages json format by their incoming date
     * 
     * @param int $linesNumber
     * 
     * @return array
     */
    public function getLastLinesAsJson(int $linesNumber): array
    {
        $lines = \frontend\models\CbLog::find()->select(
            ['imei', 'signal', 'status', 'fireproof_counter_hrn', 'fireproof_counter_card', 'collection_counter', 'notes_billiards_pcs', 'rate', 'refill_amount', 'unix_time_offset', 'created_at'])
            ->orderBy(['unix_time_offset' => SORT_DESC])
            ->limit($linesNumber)
            ->asArray()
            ->all();
        $jsonLines = [];
        $parser = new CParser();

        foreach ($lines as $line) {
            $jsonLines[$line['created_at']] = $this->convertCbLogToJson($line);
        }

        return $jsonLines;
    }

    /**
     * Converts log package into json format
     * 
     * @param array $line
     * 
     * @return string
     */
    public function convertCbLogToJson(array $line): string
    {
        $jsonData = [
            'imei' => $line['imei'],
            'type' => JsonController::LOG,
            'pac' => [
                'id' => "0",
                'prio' => "0",
                'devType' => Log::CENTRAL_BOARD,
                'utc' => $line['unix_time_offset'],
                'numberDev' => "0",
                'rssi' => $line['signal'],
                'event' => [
                    'num' => "0",
                    'cenBoard' => $line['status'],
                    'washer' => "0",
                    'dryer' => "0",
                    'cleaner' => "0",
                    'unitCards' => "0"
                    
                ],
                'devCash' => $line['refill_amount'],
                'money' => [
                    'total' => $line['fireproof_counter_hrn'],
                    'totalCards' => $line['fireproof_counter_card'],
                    'collection' => $line['collection_counter'],
                    'numberNotes' => $line['notes_billiards_pcs'],
                    'numberCoins' => "0"
                ],
                'priceMode' => "0",
                'tariff' => $line['rate'],
                'washMode' => "0",
                'washTemp' => "0",
                'washExtrac' => "0",
                'washAddition' => [
                    'prewash' => "0",
                    'rising_plus' => "0",
                    'intensive_washing' => "0"
                ],
                'counters' => [
                    'water' => "0",
                    'power' => "0"
                ]
            ]
        ];

        return json_encode($jsonData);
    }
}