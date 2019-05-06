<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\components\db\ModemLevelSignal\DbModemLevelSignalHelper;
use frontend\models\Jlog;
use frontend\models\JlogSearch;
use frontend\models\Imei;
use frontend\models\AddressBalanceHolder;
use frontend\models\AddressImeiData;
use frontend\models\Company;
use frontend\services\parser\CParser;
use frontend\services\globals\DateTimeHelper;
use frontend\services\globals\EntityHelper;
use yii\helpers\Console;

class ModemLevelSignalController extends Controller
{
    const MIN_SIGNAL_LEVEL = -128;

    public function actionTest()
    {

        return 'test';
    }

    public function getAggregatedLevelSignal(array $signalData)
    {
        $signal = 0;
        $count = 0;

        foreach ($signalData as $value) {
            if (!is_null($value)) {
                $signal += (int)$value;
            } else {
                $signal += self::MIN_SIGNAL_LEVEL;
            }

            ++$count;
        }

        return $count > 0 ? (int)$signal/$count : self::MIN_SIGNAL_LEVEL;
    }

    public function actionUpdateData($addressId, $start, $end, $step)
    {
        $address = AddressBalanceHolder::find()->where(['id' => $addressId])->limit(1)->one();
        $addressImeiData = new AddressImeiData();

        if (!$address || 
            (!$companyId = $address->company_id) ||
            (!$imeiId = $addressImeiData->getImeiIdByAddressTimestamp($address->id, $start + $step))
        ) {

            return false;
        }

        $dbHelper = new DbModemLevelSignalHelper();
        $parser = new CParser();
        $jlogSearch = new JlogSearch();
        $dateTimeHelper = new DateTimeHelper();
        $dbHelper->eraseData($address->id, $start, $end);
        $start = ($stamp = $jlogSearch->getInitializationHistoryBeginningByImeiId($imeiId)) > $start ? $stamp : $start;
        $start = $dateTimeHelper->getDayBeginningTimestamp($start);
        $baseStart = $start;
        $hasIterated = false;
        $prevImeiId = 0;
        $imeiHistory = $addressImeiData->getImeiHistoryByAddress($address);
        $imeiId = $addressImeiData->getImeiIdByAddressTimestamp($address->id, $start + $step);

        for (; $start + $step <= $end; $start += $step) {
            $imeiId = $addressImeiData->getImeiIdByAddressHistory($address, $start + $step, $imeiHistory);

            if (empty($imeiId)) {

                $imeiChanged = true;
                continue;
            }

            if ($imeiId != $prevImeiId) {
                $prevImeiId = $imeiId;
                $imeiChanged = true;
            }

            $allData = Jlog::find()->andWhere(['type_packet' => Jlog::TYPE_PACKET_INITIALIZATION,'imei_id' => $imeiId]);
            $condition = new \yii\db\conditions\BetweenCondition(
                'unix_time_offset', 'BETWEEN', $start, $start + $step
            );

            $allData = $allData->andWhere($condition)->orderBy(['unix_time_offset' => SORT_ASC])->all();

            $signalData = [];

            foreach ($allData as $item) {
                $parseData = $parser->iParse($item->packet);
                $signalData[] = $parseData['level_signal'];

                //Console::output($levelSignal."\n");
            }

            $aggregatedLevelSignal = $this->getAggregatedLevelSignal($signalData);

            if (
                !$prevAggregatedLevelSignal || $prevAggregatedLevelSignal != $aggregatedLevelSignal
                || $imeiChanged || !$insertId
            ) {
                $prevAggregatedLevelSignal = $aggregatedLevelSignal;
                $insertId = $dbHelper->insertData(
                    $imeiId, $address->id, $address->balance_holder_id, $address->company_id, 
                    $start, $start+$step, $aggregatedLevelSignal
                );
                $baseStart = $start;
            } else {
                $dbHelper->updateData($insertId, $baseStart, $start+$step, $aggregatedLevelSignal);
            }

            $imeiChanged = false;
        }

        return true;
    }
    
    public function actionUpdateDataAll($start, $end, $step, $companyId)
    {
        $dbHelper = new DbModemLevelSignalHelper();
        $entityHelper = new EntityHelper();
        $select = "id, address";
        $bInst = Company::find()->where(['id' => $companyId])->limit(1)->one();
        $inst = new AddressBalanceHolder();
        $dbHelper->getBaseUnitQueryByTimestamps($start, $end, $inst, $bInst, 'company_id', $select);
        $items = $dbHelper->getItems();

        foreach ($items as $item) {
            $address = AddressBalanceHolder::find()->where(['id' => $item['id']])->limit(1)->one();
            $addressTimestamps = $entityHelper->makeUnitTimestamps($start, $end, $address, ($step/3600));
            list($start, $end) = [$addressTimestamps['start'], $addressTimestamps['end']];
            $this->actionUpdateData($item['id'], $start, $end, $step);
            Console::output("address ".$item['address']." has been passed\n");
        }
    }
}