<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\components\db\ModemLevelSignal\DbModemLevelSignalHelper;
use frontend\models\Jlog;
use frontend\models\JlogSearch;
use frontend\models\Imei;
use frontend\models\ImeiData;
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
    const PING_TIME_INTERVAL = 900;
    const DATA_PACKET_INTERVAL = 1800;

    /**
     * Aggregates level signal by MIN value
     * 
     * @param array $signalData
     * 
     * @return int
     */
    public function getAggregatedLevelSignal(array $signalData)
    {
        $signal = 10000;
        $count = 0;
        $hasAssigned = false;

        foreach ($signalData as $value) {
            if (is_null($value)) {

                return self::MIN_SIGNAL_LEVEL;
            } elseif ($signal > $value) {
                $signal = $value;
                $hasAssigned = true;
            }
        }

        return $hasAssigned ? $signal : self::MIN_SIGNAL_LEVEL;
    }

    /**
     * Gets aggregated level signal by data 
     * 
     * @param array $signalData
     * @param int $imeiId
     * @param int $start
     * @param int $end
     * @param int $prevNonMinSignalLevel
     * @param int $monitoringStep
     * 
     * @return int
     */
    public function getAggregatedLevelSignalByData(
        array $signalData, $imeiId, $start, $end, $prevNonMinSignalLevel, $monitoringStep
    )
    {
        $aggregatedLevelSignal = $this->getAggregatedLevelSignal($signalData);

        if (empty($signalData) && !is_null($prevNonMinSignalLevel)) {
            if ($monitoringStep == self::DATA_PACKET_INTERVAL) {
                $aggregatedLevelSignal = $this->getAggregatedLevelSignalByDataPacket(
                    $imeiId, $start, $end, $prevNonMinSignalLevel
                );
            } else {
                $aggregatedLevelSignal = $this->getAggregatedLevelSignalByPing(
                    $imeiId, $end, $prevNonMinSignalLevel
                );
            }
        }

        return  $aggregatedLevelSignal;
    }

    /**
     * Updates data by address id and timestamps 
     * 
     * @param int $addressId
     * @param int $start
     * @param int $end
     * @param int $step
     * @param int $monitoringStep
     */
    public function actionUpdateData($addressId, $start, $end, $step, $monitoringStep)
    {
        $address = AddressBalanceHolder::find()->where(['id' => $addressId])->limit(1)->one();
        $addressString = $address->address.", ".$address->floor;

        $dbHelper = new DbModemLevelSignalHelper();
        $parser = new CParser();
        $jlogSearch = new JlogSearch();
        $dateTimeHelper = new DateTimeHelper();
        $dbHelper->eraseData($address->id, $start, $end);
        $stamp = $jlogSearch->getInitializationHistoryBeginningByAddressString($addressString);
        $start = $stamp > $start ? ($stamp < $end ? $stamp : $start) : $start;
        $start = $dateTimeHelper->getDayBeginningTimestamp($start);
        $prevAggregatedLevelSignal = null;
        $prevNonMinSignalLevel = $jlogSearch->getLastLevelSignalByAddressAndTimestamp($addressString, $start);
        $imeiId = $jlogSearch->getImeiIdByAddressStringAndInitialTimestamp($addressString, $start);
        $baseStart = $start;
        $insertId = 0;

        for (; $start + $step <= $end; $start += $step) {
            $allData = Jlog::find()->andWhere(['type_packet' => Jlog::TYPE_PACKET_INITIALIZATION, 'address' => $addressString]);
            $startStamp = $start + $step - $monitoringStep;
            $condition = new \yii\db\conditions\BetweenCondition(
                'unix_time_offset', 'BETWEEN', $startStamp, $start + $step
            );

            $allData = $allData->andWhere($condition)->orderBy(['unix_time_offset' => SORT_ASC])->all();
            $signalData = [];

            foreach ($allData as $item) {
                $parseData = $parser->iParse($item->packet);
                $signalData[] = $parseData['level_signal'];
                $imeiId = $item->imei_id;
            }

            $aggregatedLevelSignal = $this->getAggregatedLevelSignalByData(
                $signalData, $imeiId, $startStamp, $start + $step, $prevNonMinSignalLevel, $monitoringStep
            );

            $newRecordCondition = 
                is_null($prevAggregatedLevelSignal) || $prevAggregatedLevelSignal != $aggregatedLevelSignal
                || !$insertId;

            if ($newRecordCondition) {
                $prevAggregatedLevelSignal = $aggregatedLevelSignal;
                if ($aggregatedLevelSignal != self::MIN_SIGNAL_LEVEL) {
                    $prevNonMinSignalLevel = $aggregatedLevelSignal;
                }

                $insertId = $dbHelper->insertData(
                    $imeiId, $address->id, $address->balance_holder_id, $address->company_id, 
                    $start, $start+$step, $aggregatedLevelSignal
                );
                $baseStart = $start;
            } else {
                $dbHelper->updateData($insertId, $baseStart, $start+$step, $aggregatedLevelSignal);
            }
        }
    }

    /**
     * Updates data by company id and timestamps 
     * 
     * @param int $start
     * @param int $end
     * @param int $step
     * @param int $companyId
     * @param int $monitoringStep
     */
    public function actionUpdateDataByCompanyId($start, $end, $step, $companyId, $monitoringStep)
    {
        $dbHelper = new DbModemLevelSignalHelper();
        $entityHelper = new EntityHelper();
        $select = "id, address";
        $bInst = Company::find()->where(['id' => $companyId])->limit(1)->one();
        $inst = new AddressBalanceHolder();
        $dbHelper->getExistingUnitQueryByTimestamps($start, $end, $inst, $bInst, 'company_id', $select);
        $items = $dbHelper->getItems();

        foreach ($items as $item) {
            $address = AddressBalanceHolder::find()->where(['id' => $item['id']])->limit(1)->one();
            $addressTimestamps = $entityHelper->makeUnitTimestamps($start, $end, $address, ($step/3600));
            list($baseStart, $baseEnd) = [$addressTimestamps['start'], $addressTimestamps['end']];
            $this->actionUpdateData($item['id'], $baseStart, $baseEnd, $step, $monitoringStep);
            Console::output("address ".$item['address']." has been processed\n");
        }
    }

    /**
     * Updates data by meta timestamp 
     * 
     * @param string $meta
     * @param int $step
     */
    public function actionUpdateDataByMeta($meta, $step)
    {
        $dateTimeHelper = new DateTimeHelper();
        $dbHelper = new DbModemLevelSignalHelper();
        $monitoringStep = self::DATA_PACKET_INTERVAL;

        switch ($meta) {
            case "today":
                $start = $dateTimeHelper->getDayBeginningTimestamp($end=time());
                break;
            case "lastday":
                $end = $dateTimeHelper->getDayBeginningTimestamp(time());
                $start = $end - 3600*24;
                break;
            case "all":
                $start = 0;
                $end = time();
                break;
            case "lastping":
                $end = time();
                $monitoringStep = self::PING_TIME_INTERVAL;
                $start = $end - $monitoringStep;
        }

        $queryString = "SELECT id,name FROM company WHERE created_at < :end ".
                       "AND (is_deleted = false OR (is_deleted = true AND deleted_at > :start))".
                       "ORDER BY id";
        $bindValues = [':start' => $start, ':end' => $end];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);
        $items = $command->queryAll();

        foreach ($items as $item) {
            $this->actionUpdateDataByCompanyId($start, $end, $step, $item['id'], $monitoringStep);
            Console::output("company ".$item['name']." has been processed\n\n");
        }
    }

    /**
     * Gets level signal depending on whether data packet exist
     * 
     * @param int $imeiId
     * @param int $start
     * @param int $end
     * @param int $prevSignal
     * 
     * @return int
     */
    public function getAggregatedLevelSignalByDataPacket($imeiId, $start, $end, $prevSignal)
    {
        $queryString = "SELECT id FROM imei_data WHERE created_at >= :start AND created_at <= :end ";
        $queryString .= "AND imei_id = :imei_id LIMIT 1;";
        $bindValues = [':start' => $start, ':end' => $end, ':imei_id' => $imeiId];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return !empty($command->queryScalar()) ? $prevSignal : self::MIN_SIGNAL_LEVEL;
    }

    /**
     * Gets level signal depending on imei ping
     * 
     * @param int $imeiId
     * @param int $end
     * @param int $prevSignal
     * 
     * @return int
     */
    public function getAggregatedLevelSignalByPing($imeiId, $end, $prevSignal)
    {
        $imei = Imei::find()->where(['id' => $imeiId])->limit(1)->one();

        if (!empty($imei)) {
            $ping = $imei->getLastPingValue();

            if (empty($ping) || $end - $ping > self::PING_TIME_INTERVAL) {

                return self::MIN_SIGNAL_LEVEL;
            }

            return $prevSignal;
        }

        return self::MIN_SIGNAL_LEVEL;
    }

    /**
     * Makes data log with terminal not in touch status
     * 
     * @param string $addressString
     * @param int $start
     * @param \frontend\models\Jlog
     * @param \frontend\models\JlogSearch
     * 
     * @return bool
     */
    public function makeDataLog($addressString, $start, $jlog, $jlogSearch)
    {
        $item = $jlogSearch->getLastDataItemByAddressAndTimestamp($addressString, $start);

        if ($item && $item->packet) {
            $item->packet = explode("_", $item->packet)[0];
            $jlog->makeDataLogByItem($item, $start, ImeiData::CP_STATUS_TERMINAL_NOT_IN_TOUCH);

            return true;
        }

        return false;
    }

    /**
     * Makes data packet records in case of terminal not in touch
     * 
     * @param int $addressId
     * @param int $start
     * @param int $end
     * 
     * @return bool
     */
    public function actionMakeNotInTouchLog($addressId, $start, $end)
    {
        $address = AddressBalanceHolder::find()->where(['id' => $addressId])->limit(1)->one();
        $addressString = $address->address.", ".$address->floor;
        $jlogSearch = new JlogSearch();
        $jlog = new Jlog();

        $imeiId = $jlogSearch->getImeiIdByAddressStringAndInitialTimestamp($addressString, $start);

        if ($end - $start <= self::PING_TIME_INTERVAL) {

            return $this->actionMakeNotInTouchLogByPing($imeiId, $addressString, $start, $end, $jlog, $jlogSearch);
        }

        if (!empty($imeiId)) {
            $levelSignal = $this->getAggregatedLevelSignalByDataPacket($imeiId, $start, $end, 0);
            $hasData = empty($levelSignal) ? true : false;

            if (!$hasData) {

                return $this->makeDataLog($addressString, $start, $jlog, $jlogSearch);
            }
        }

        return false;
    }

    /**
     * Makes data records in case of terminal not in touch but by ping
     * 
     * @param int $addressId
     * @param int $start
     * @param int $end
     * @param \frontend\models\Jlog
     * @param \frontend\models\JlogSearch
     * 
     * @return bool
     */
    public function actionMakeNotInTouchLogByPing($imeiId, $addressString, $start, $end, $jlog, $jlogSearch)
    {
        $imei = Imei::find()->where(['id' => $imeiId])->limit(1)->one();

        if (!empty($imei)) {
            $ping = $imei->getLastPingValue();

            if (empty($ping) || $end - $ping > self::PING_TIME_INTERVAL) {

                return $this->makeDataLog($addressString, $start, $jlog, $jlogSearch);
            }
        }

        return false;
    }

    /**
     * Makes data packet records in case of terminal not in touch and by timestamps
     * 
     * @param int $start
     * @param int $end
     * @param int $step
     * @param int $rate
     */
    public function actionMakeNotInTouchLogByTimestamps($start, $end, $step, $rate)
    {
        $queryString = " SELECT id, address FROM address_balance_holder WHERE created_at < :end AND ";
        $queryString .= "(is_deleted = false OR (is_deleted = true AND deleted_at > :start))";
        $bindValues = [':start' => $start, ':end' => $end];
        $items = Yii::$app->db->createCommand($queryString)->bindValues($bindValues)->queryAll();
        $entityHelper = new EntityHelper();

        foreach ($items as $item) {
            $address = AddressBalanceHolder::find()->where(['id' => $item['id']])->limit(1)->one();
            $addressTimestamps = $entityHelper->makeUnitTimestamps($start, $end, $address, ($step/3600));
            list($baseStart, $baseEnd) = [$addressTimestamps['start'], $addressTimestamps['end']];

            for (; $baseStart < $baseEnd; $baseStart += $rate) {
                $this->actionMakeNotInTouchLog($address->id, $baseStart, $baseStart + $step);
            }

            Console::output("Address ".$item['address']." has been processed\n\n");
        }
    }

    /**
     * Makes data packet records in case of terminal not in touch and for last time
     * 
     * @param int $timeInterval
     * @param int $step
     */
    public function actionMakeNotInTouchLogForLastTime($timeInterval, $step)
    {
        $end = time();
        $start = $end - $timeInterval;
        $rate = $step;
        $this->actionMakeNotInTouchLogByTimestamps($start, $end, $step, $rate);
    }
    
    /**
     * Makes data packet records in case of terminal not in touch and for last time
     * 
     * @param int $start
     * @param int $step
     */
    public function actionMakeNotInTouchLogForSinceTime($start, $step)
    {
        $end = time();
        $start = $start;
        $rate = 300;
        $this->actionMakeNotInTouchLogByTimestamps($start, $end, $step, $rate);
    }

    /**
     * Deletes all not in touch logs
     * 
     */
    public function actionDeleteAllNotInTouchLogs()
    {
        $unixTimeOffset = -1;
        $parser = new CParser();
        do {
            $searchString = "*".ImeiData::CP_STATUS_TERMINAL_NOT_IN_TOUCH;
            $searchStringLength = strlen($searchString);
            $items = Jlog::find()->select('id, packet, unix_time_offset')
                                ->where(['type_packet' => Jlog::TYPE_PACKET_DATA])
                                ->andWhere(['>=','unix_time_offset', $unixTimeOffset])
                                ->andWhere([
                                "LOCATE(
                                    '{$searchString}', SUBSTRING(packet, CHAR_LENGTH(packet) -{$searchStringLength} + 1)
                                )" => 1])
                                ->orderBy(['unix_time_offset' => SORT_ASC])
                                ->limit(10000)
                                ->all();
            if (!$items) {
                break;
            }

            foreach ($items as $item) {
                $unixTimeOffset = $item->unix_time_offset;
                echo "item \"".$item->packet."\" has been deleted\n";
                $item->delete();
            }
        }
        while(true);

        $unixTimeOffset = -1;
        do {
            $searchString = "*".self::MIN_SIGNAL_LEVEL;
            $searchStringLength = strlen($searchString);
            $items = Jlog::find()->select('id, packet, unix_time_offset')
                                ->where(['type_packet' => Jlog::TYPE_PACKET_INITIALIZATION])
                                ->andWhere(['>=','unix_time_offset', $unixTimeOffset])
                                ->andWhere([
                                "LOCATE(
                                    '{$searchString}', SUBSTRING(packet, CHAR_LENGTH(packet) -{$searchStringLength} + 1)
                                )" => 1])
                                ->orderBy(['unix_time_offset' => SORT_ASC])
                                ->limit(10000)
                                ->all();
            if (!$items) {
                break;
            }

            foreach ($items as $item) {
                $unixTimeOffset = $item->unix_time_offset;
                echo "item \"".$item->packet."\" has been deleted\n";
                $item->delete();
            }
        }
        while(true);
    }
}