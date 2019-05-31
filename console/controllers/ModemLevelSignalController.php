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
     * @param bool $lastStatus
     * 
     * @return bool
     */
    public function actionMakeNotInTouchLog($addressId, $start, $end, $lastStatus)
    {
        $address = AddressBalanceHolder::find()->where(['id' => $addressId])->limit(1)->one();
        $addressString = $address->address.", ".$address->floor;
        $jlogSearch = new JlogSearch();
        $jlog = new Jlog();
        $rate = 300;

        $imeiId = $jlogSearch->getImeiIdByAddressStringAndInitialTimestamp($addressString, $start);

        if ($end - $start <= self::PING_TIME_INTERVAL) {

            return $this->actionMakeNotInTouchLogByPing($imeiId, $addressString, $start, $end, $jlog, $jlogSearch, $lastStatus);
        }

        if (!empty($imeiId)) {
            $levelSignal = $this->getAggregatedLevelSignalByDataPacket($imeiId, $start, $end, 0);
            $hasData = empty($levelSignal) ? true : false;

            if (!$hasData) {
                $this->makeDataLog($addressString, $start, $jlog, $jlogSearch);

                return true;
            } elseif ($lastStatus) {
                $this->makeDataLog($addressString, $end - $rate, $jlog, $jlogSearch);

                return false;
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
     * @param bool $lastStatus
     * 
     * @return bool
     */
    public function actionMakeNotInTouchLogByPing($imeiId, $addressString, $start, $end, $jlog, $jlogSearch, $lastStatus)
    {
        $imei = Imei::find()->where(['id' => $imeiId])->limit(1)->one();
        $rate = 300;

        if (!empty($imei)) {
            $ping = $imei->getLastPingValue();

            if (empty($ping) || $end - $ping > self::PING_TIME_INTERVAL) {
                $this->makeDataLog($addressString, $start, $jlog, $jlogSearch);

                return true;
            } elseif ($lastStatus) {
                $this->makeDataLog($addressString, $end - $rate, $jlog, $jlogSearch);

                return false;
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
        $dateTimeHelper = new DateTimeHelper();
        $jlogSearch = new JlogSearch();
        $byStamp = 300;

        foreach ($items as $item) {
            $address = AddressBalanceHolder::find()->where(['id' => $item['id']])->limit(1)->one();
            $addressString = $address->address.", ".$address->floor;
            $addressTimestamps = $entityHelper->makeUnitTimestamps($start, $end, $address, ($step/3600));
            list($baseStart, $baseEnd) = [$addressTimestamps['start'], $addressTimestamps['end']];
            $initialPoints = $jlogSearch->getFirstLastPacketItemsByAddress($addressString);
            $baseStart = $baseStart < $initialPoints['first'] ? $initialPoints['first'] : $baseStart;
            $baseStart = $dateTimeHelper->getRoundedTimestamp($baseStart, $byStamp);
            $lastStatus = false;

            for (; $baseStart < $baseEnd; $baseStart += $rate) {

                if ($baseStart + $step <= $baseEnd) {
                    $lastStatus = $this->actionMakeNotInTouchLog($address->id, $baseStart, $baseStart + $step, $lastStatus);
                } else {
                    break;
                }
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
        $dateTimeHelper = new DateTimeHelper();
        $byStamp = 300;
        $end = $dateTimeHelper->getRoundedTimestamp(time(), $byStamp, true);
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
        $dateTimeHelper = new DateTimeHelper();
        $byStamp = 300;
        $end = $dateTimeHelper->getRoundedTimestamp(time(), $byStamp, true);
        $start = $dateTimeHelper->getRoundedTimestamp($start, $byStamp);
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