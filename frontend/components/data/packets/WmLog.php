<?php

namespace frontend\components\data\packets;

use Yii;
use yii\base\Component;
use frontend\services\parser\CParser;
use api\modules\v2d00\controllers\JsonController;
use api\modules\v2d00\UseCase\Log\Log;

/**
 * Wasine Mashine logs parser into json format
 */
class WmLog extends Base {

    /**
     * Gets  last log packages json format by their incoming date
     * 
     * @param int $linesNumber
     * 
     * @return array
     */
    public function getLastLinesAsJson(int $linesNumber): array
    {
        $lines = \frontend\models\WmLog::find()->select(
            ['imei', 'signal', 'status', 'number', 'price', 'account_money', 'washing_mode', 'wash_temperature', 'spin_type', 'prewash', 'rinsing', 'intensive_wash', 'unix_time_offset', 'created_at'])
            ->orderBy(['unix_time_offset' => SORT_DESC])
            ->limit($linesNumber)
            ->asArray()
            ->all();
        $jsonLines = [];
        $parser = new CParser();

        foreach ($lines as $line) {
            $jsonLines[$line['created_at']] = $this->convertWmLogToJson($line);
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
    public function convertWmLogToJson(array $line): string
    {
        $jsonData = [
            'imei' => $line['imei'],
            'type' => JsonController::LOG,
            'pac' => [
                'id' => "0",
                'prio' => "0",
                'devType' => Log::WASH_MACHINE,
                'utc' => $line['unix_time_offset'],
                'numberDev' => $line['number'],
                'rssi' => $line['signal'],
                'event' => [
                    'num' => "0",
                    'washer' => $line['status'],
                    'cenBoard' => "0",
                    'dryer' => "0",
                    'cleaner' => "0",
                    'unitCards' => "0"
                    
                ],
                'devCash' => $line['account_money'],
                'money' => [
                    'total' => "0",
                    'totalCards' => "0",
                    'collection' => "0",
                    'numberNotes' => "0",
                    'numberCoins' => "0"
                ],
                'priceMode' => $line['price'],
                'tariff' => "0",
                'washMode' => $line['washing_mode'],
                'washTemp' => $line['wash_temperature'],
                'washExtrac' => $line['spin_type'],
                'washAddition' => [
                    'prewash' => $line['prewash'],
                    'rising_plus' => $line['rinsing'],
                    'intensive_washing' => $line['intensive_wash']
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