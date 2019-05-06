<?php

namespace frontend\storages;
use frontend\models\WmMashineDataSearch;
use frontend\models\BalanceHolder;
use Yii;
use frontend\services\globals\DateTimeHelper;
use frontend\components\db\ModemLevelSignal\DbModemLevelSignalHelper;
use console\controllers\ModemLevelSignalController;

/**
 * Class ModemStatStorage
 * @package frontend\storages;
 */
class ModemStatStorage extends MashineStatStorage
{
    const STEP = 1800;
    const MIN_LEVEL_SIGNAL = -128;

    /**
     * Aggregates modem level signals for google graph
     * 
     * @param int $start
     * @param int $end
     * @param array $options
     * 
     * @return array
     */
    public function aggregateModemLevelSignalsForGoogleGraph($start, $end, $options)
    {
        $dbHelper = new DbModemLevelSignalHelper();

        $addressesInfo = [
            [
                'address' => 'вул. Мельникова 36, 1',
                'id' => 40
            ],
            [
                'address' => 'вул. Ломоносова 63',
                'id' => 61
            ]
        ];

        $addressesInfo = $dbHelper->getAddressesByTimestampsAndCompanyId($start, $end, 1);

        $lines = [];
        $titles = [''];

        for ($baseStart = $start; $baseStart <= $end; $baseStart+= self::STEP) {
            $item = [];
            $item[] = date("d.m H:i", $baseStart);

            foreach ($addressesInfo as $addressInfo) {
                $item[]= self::MIN_LEVEL_SIGNAL;
            }

            $lines[] = $item;
        }

        $iterationCounter = 1;

        foreach ($addressesInfo as $addressInfo) {
            $data = $dbHelper->getDataByAddressIdAndTimestamps($addressInfo['id'], $start, $end);
            foreach ($data as $item) {
                $stamp = $item['start'];
                $stampEnd = $item['end'];
                $index = ($stamp - $start) / self::STEP;
                $number = ($stampEnd - $stamp) / self::STEP;

                for ($i = $index; $i < $index + $number; ++$i) {
                    $lines[$i][$iterationCounter] = $item['level_signal'];
                }
            }

            $titles[] = $addressInfo['name'];
            ++$iterationCounter;
        }

        return ['titles' => $titles, 'lines' => $lines, 'options' => $options];
    }

    /**
     * Aggregates  current day modem level signals for google graph
     * 
     * @param int $start
     * @param int $end
     * @param array $options
     * 
     * @return array
     */
    public function aggregateCurrentModemLevelSignalsForGoogleGraph($options)
    {
         $controller = new ModemLevelSignalController('ModemLevelSignalController', Yii::$app->getModule('ModemLevelSignal'));
    }
}