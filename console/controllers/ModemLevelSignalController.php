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
     * Updates data by address id and timestamps 
     * 
     * @param int $addressId
     * @param int $start
     * @param int $end
     * @param int $step
     */
    public function actionUpdateData($addressId, $start, $end, $step)
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
        $imeiId = $jlogSearch->getImeiIdByAddressStringAndInitialTimestamp($addressString, $start);
        $baseStart = $start;
        $insertId = 0;

        for (; $start + $step <= $end; $start += $step) {
            $allData = Jlog::find()->andWhere(['type_packet' => Jlog::TYPE_PACKET_INITIALIZATION, 'address' => $addressString]);
            $condition = new \yii\db\conditions\BetweenCondition(
                'unix_time_offset', 'BETWEEN', $start, $start + $step
            );

            $allData = $allData->andWhere($condition)->orderBy(['unix_time_offset' => SORT_ASC])->all();
            $signalData = [];

            foreach ($allData as $item) {
                $parseData = $parser->iParse($item->packet);
                $signalData[] = $parseData['level_signal'];
                $imeiId = $item->imei_id;
            }

            $aggregatedLevelSignal = $this->getAggregatedLevelSignal($signalData);
            $newRecordCondition = 
                is_null($prevAggregatedLevelSignal) || $prevAggregatedLevelSignal != $aggregatedLevelSignal
                || !$insertId;

            if ($newRecordCondition) {
                $prevAggregatedLevelSignal = $aggregatedLevelSignal;
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
     * 
     * @return bool
     */
    public function actionUpdateDataByCompanyId($start, $end, $step, $companyId)
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
            $this->actionUpdateData($item['id'], $baseStart, $baseEnd, $step);
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
        }

        $queryString = "SELECT id,name FROM company WHERE created_at < :end ".
                       "AND (is_deleted = false OR (is_deleted = true AND deleted_at > :start))".
                       "ORDER BY id";
        $bindValues = [':start' => $start, ':end' => $end];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);
        $items = $command->queryAll();

        foreach ($items as $item) {
            $this->actionUpdateDataByCompanyId($start, $end, $step, $item['id']);
            Console::output("company ".$item['name']." has been processed\n\n");
        }
    }
}