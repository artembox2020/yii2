<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\BalanceHolder;
use frontend\models\WmMashine;
use frontend\models\BalanceHolderSummarySearch;
use frontend\models\ImeiData;
use frontend\models\Jlog;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use yii\helpers\ArrayHelper;

/**
 * BalanceHolderSummaryDetailedSearch represents the model to make detailed summary journal
 */
class BalanceHolderSummaryDetailedSearch extends BalanceHolderSummarySearch
{
    /**
     * Gets incomes aggregated data
     *
     * @param ActiveDataProvider $dataProvider
     * @param int $year
     * @param int $month
     * @param int $days
     * @param timestamp $timestamp
     * @return array
     */
    public function getMashineIncomesAggregatedData($dataProvider, $year, $month, $days, $timestamp)
    {
        $summaryTotal = [];
        $countTotal = 0;
        $data = [];
        $k = 0;
        $timestampEnd = $this->getTimestampByYearMonthDay($year, $month, $days, false);
        $timestampStart = $this->getTimestampByYearMonthDay($year, $month, '01', true);
        foreach ($dataProvider->query->all() as $balanceHolder) {
            foreach ($balanceHolder->getAddressBalanceHoldersQueryByTimestamp($timestampStart, $timestampEnd)->all() as $address) {
                $data[$k] = [];
                $mashineQuery = $this->getAllMashinesQueryByYearMonth($year, $month, $address);
                $incomes = $this->getMashineIncomesByYearMonth($year, $month, $days, $mashineQuery);
                $countTotal += $mashineQuery->count();

                foreach ($incomes as $mashine_id => $income) {
                    $data[$k] = [];    
                    for ($j = 1; $j <= $days; ++$j) {
                        $summaryTotal[$j] += $this->parseFloat($income[$j]['income'], 2);
                        $class = $this->makeClassByIncome($income[$j]);
                        $data[$k][$j] = [
                            'timestampStart' => $this->getTimestampByYearMonthDay($year, $month, $j, true),
                            'timestampEnd' => $this->getTimestampByYearMonthDay($year, $month, $j, false),
                            'class' => $class
                        ];
                    }
                    $data[$k++]['incomes'] = $income;
                }
            }
        }
        $data['summaryTotal'] = $summaryTotal;
        $data['countTotal'] = $countTotal;

        return $data;
    }

    /**
     * Gets mashines incomes by year and month
     *
     * @param int $year
     * @param int $month
     * @param int $days
     * @param ActiveDbQuery $mashinesQuery
     *
     * @return decimal
     */ 
    public function getMashineIncomesByYearMonth($year, $month, $days, $mashinesQuery)
    {
        $incomes = [];
        $timestampEnd = $this->getTimestampByYearMonthDay($year, $month, $days, false);
        $timestampStart = $this->getTimestampByYearMonthDay($year, $month, '01', true);
        if ($mashinesQuery->count() == 0) {
            for ($i = 1; $i <= $days; ++$i) {
                $arrs[$i] = [ 'income' => null, 'deleted' => false, 'created' => false, 'idleHours' => 0];
            }
            $incomes[0] = $arrs;

            return $incomes;
        }

        foreach ($mashinesQuery->all() as $mashine) {
            $incomes[$mashine->id] = $this->getMashineIncomeByTimestamps($timestampStart, $timestampEnd + 1, $days, $mashine);
        }

        return $incomes;
    }

    /**
     * Gets mashine income value
     *
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param WmMashine $mashine
     * @return decimal
     */
    public function getMashineIncomeValueByTimestamps($startTimestamp, $endTimestamp, $mashine)
    {
        $entityHelper = new EntityHelper();
        $selectString = 'id, bill_cash, created_at';
        $baseQuery = $entityHelper->getBaseUnitQueryByTimestamps(
            $startTimestamp,
            $endTimestamp,
            new WmMashineData(),
            $mashine,
            'mashine_id',
            $selectString
        );
        if ($baseQuery->count() == 0) {
            $income = null;
        } else {
            $nonZeroIntervals = $entityHelper->makeNonZeroIntervalsByTimestamps(
                $startTimestamp,
                $endTimestamp,
                new WmMashineData(),
                $mashine,
                'mashine_id',
                $selectString,
                'bill_cash'
            );

            $income = 0;
            $isFirst = true;
            foreach ($nonZeroIntervals as $interval) {
                $income += $entityHelper->getUnitIncomeByNonZeroTimestamps(
                    $interval['start'],
                    $interval['end'],
                    new WmMashineData(),
                    $mashine,
                    'mashine_id',
                    $selectString,
                    'bill_cash',
                    $isFirst
                );
                $isFirst = false;
            }
        }

        return $income;
    }

    /**
     * Gets date and sum encashment by timestamps and mashine id
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param int $mashineId
     * @return array
     */
    public function getDateAndSumEncashmentByTimestamps($start, $end, $mashineId)
    {
        global $encashmentHistory;
        $mashineData = new WmMashineData();
        $dayBeginning = $this->getDayBeginningTimestampByTimestamp($start);
        if (empty($encashmentHistory[$mashineId])) {
            $nowTimestamp = time() + Jlog::TYPE_TIME_OFFSET;
            $encashmentHistory[$mashineId] = $mashineData->getEncashmentHistoryByMashineId($mashineId, $start, $nowTimestamp);
        }

        if (empty($encashmentHistory[$mashineId][$dayBeginning])) {

            return [
                'encasment_date' => null,
                'encasment_sum' => null
            ];
        }

        $encashmentSum = 0;
        $encashmentDate = null;
        foreach ($encashmentHistory[$mashineId][$dayBeginning] as $item) {
            $encashmentSum += $item['bill_cash'];
            if (empty($encashmentDate)) {
                $encashmentDate = $item['created_at'];
            }
        }

        return [
            'encashment_date' => $encashmentDate,
            'encashment_sum' => $encashmentSum
        ];
    }

    /**
     * Gets mashine detailed statistics
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param WmMashine $mashine
     *
     * @return decimal
     */     
    public function getMashineDetailedStatisticsByTimestamps($startTimestamp, $endTimestamp, $mashine)
    {
        $entityHelper = new EntityHelper();
        $idleHoursInfo = $entityHelper->getUnitIdleHoursByTimestamps(
            $startTimestamp,
            $endTimestamp,
            new WmMashineData(),
            $mashine,
            'mashine_id',
            'created_at, mashine_id',
            self::IDLE_TIME_HOURS
        );
        list($idleHours, $allHours) = [$idleHoursInfo['idleHours'], $idleHoursInfo['allHours']];
        $idleHours = $this->parseFloat($idleHours, 2);

        if ($idleHours < self::IDLE_TIME_HOURS) {
            $idleHours = 0;
        }

        $deleted_at = $mashine->is_deleted ? $mashine->deleted_at : 0;
        if ($deleted_at < $endTimestamp && $deleted_at >= $startTimestamp) {
            $is_deleted = true;
        } else {
            $is_deleted = false;
        }

        $created_at = $mashine->created_at;
        if ($created_at < $endTimestamp && $created_at >= $startTimestamp) {
            $is_created = true;
        } else {
            $is_created = false;
        }

        $encashmentInfo = $this->getDateAndSumEncashmentByTimestamps($startTimestamp, $endTimestamp, $mashine->id);
        list($encashment_date, $encashment_sum) = [$encashmentInfo['encashment_date'], $encashmentInfo['encashment_sum']];

        return [$idleHours, $allHours, $is_deleted, $is_created, $encashment_date, $encashment_sum];
    }

    /**
     * Gets mashine address
     *
     * @param WmMashine$mashine
     * @return array
     */
    public function getMashineAddress($mashine)
    {
        $imei = Imei::findOne($mashine->imei_id);

        return AddressBalanceHolder::findOne($imei->address_id);
    }

    /**
     * Makes and returns empty income item
     *
     * @return array
     */ 
    public function getEmptyIncome()
    {

        return [
            'income' => null,
            'deleted' => false,
            'created' => false,
            'idleHours' => 0,
            'imei' => false
        ];
    }

    /**
     * General function to get mashine incomes
     * @param timestamp $start
     * @param timestamp $end
     * @param int $days
     * @param WmMashine $mashine
     *
     * @return array
     */ 
    public function getMashineIncomeByTimestamps($start, $end, $days, $mashine)
    {
        $incomes = [];
        $stepInterval = 3600 * 24;
        $entityHelper = new EntityHelper();
        $jSummary = new Jsummary();
        $todayTimestamp = $this->getDayBeginningTimestampByTimestamp(time() + Jlog::TYPE_TIME_OFFSET);
        $nextMonthTimestamp = $this->getNextMonthBeginningTimestamp();
        $address = $this->getMashineAddress($mashine);
        $beginningTimestamp = $this->getDayBeginningTimestampByTimestamp($address->created_at);
        $historyIncomes = $jSummary->getDetailedIncomes($start, $end - 1, $todayTimestamp, $mashine);
        $historyDays = array_keys($historyIncomes);
        $imei = Imei::find()->where(['id' => $mashine->imei_id])->one();

        for ($i = 1; $i <= $days; ++$i) {
            $startTimestamp = $start + ($i - 1) * $stepInterval;
            $endTimestamp = $startTimestamp + $stepInterval;

            // make average income for the 1st number of the next month until the first actual results
            if (
                ($startTimestamp == $nextMonthTimestamp) || ($startTimestamp == $todayTimestamp && $i == 1)
            )
            {
                // if timestamp refers to the next month set income to null
                if ($startTimestamp == $nextMonthTimestamp) {
                    $income = null;
                } else {
                    $income = $this->getMashineIncomeValueByTimestamps($startTimestamp, $endTimestamp, $mashine);
                }

                if (is_null($income)) {

                    // if timestamp is today and it is 1st day make correct last month
                    if ($startTimestamp == $todayTimestamp && $i == 1) {
                        $lastMonth = $this->getLastMonth($nextMonthTimestamp - 1);
                    } else {
                        $lastMonth = $this->getLastMonth($nextMonthTimestamp);
                    }

                    // get last month year
                    $year = date('Y', $nextMonthTimestamp);
                    if ($lastMonth == '12') {
                        --$year;
                    }

                    $numberOfDays = $this->getDaysByMonths($year)[$lastMonth];
                    $startStamp = strtotime($year.'-'.$lastMonth.'-01 00:00:00');
                    $endStamp = $start + $stepInterval*$numberOfDays;
                    $lastMonthIncomes = $this->getMashineIncomeByTimestamps($startStamp, $endStamp, $numberOfDays, $mashine);
                    $income = $this->getAverageIncome($lastMonthIncomes);
                }

                list($idleHours, $allHours, $is_deleted, $is_created, $encashment_date, $encashment_sum)
                 = 
                $this->getMashineDetailedStatisticsByTimestamps($startTimestamp, $endTimestamp, $mashine);

                $incomes[$i] = [
                    'income' => $income,
                    'deleted' => $is_deleted,
                    'created' => $is_created,
                    'idleHours' => $idleHours,
                    'allHours' => $allHours,
                    'imei' => !empty($imei) ? $imei->imei : false,
                    'mashine_id' => $mashine->id,
                    'encashment_date' => $encashment_date,
                    'encashment_sum' => $encashment_sum
                ];

                $this->saveDetailedHistoryItem(
                    $incomes[$i], $mashine->address_id, $mashine->imei_id, $mashine->id, $startTimestamp, $endTimestamp
                );

                continue;
            }

            // read from history, if present
            if (in_array($i, $historyDays)) {
                $incomes[$i] = $historyIncomes[$i];
                continue;
            }

            // fill with zeroes all future days and before address has been created
            if ($todayTimestamp < $startTimestamp) {
                for (; $i <= $days; ++$i) {
                    $incomes[$i] = $this->getEmptyIncome();
                }
                break;
            }

            // fill with zeroes before address has been created 
            if ($startTimestamp < $beginningTimestamp) {
                $incomes[$i] = $this->getEmptyIncome();
                continue;
            }

            // set null income for deleted and not created mashines
            if ($mashine->created_at >= $endTimestamp || ($mashine->is_deleted && $mashine->deleted_at <= $startTimestamp)) {
                $incomes[$i] = $this->getEmptyIncome();
                continue;
            }

            $income = $this->getMashineIncomeValueByTimestamps($startTimestamp, $endTimestamp, $mashine);

            list($idleHours, $allHours, $is_deleted, $is_created, $encashment_date, $encashment_sum)
             = 
            $this->getMashineDetailedStatisticsByTimestamps($startTimestamp, $endTimestamp, $mashine);

            $incomes[$i] = [
                'income' => $income,
                'deleted' => $is_deleted,
                'created' => $is_created,
                'idleHours' => $idleHours,
                'allHours' => $allHours,
                'imei' => !empty($imei) ? $imei->imei : false,
                'address_id' => $mashine->address_id,
                'imei_id' => $mashine->imei_id,
                'mashine_id' => $mashine->id,
                'encashment_date' => $encashment_date,
                'encashment_sum' => $encashment_sum
            ];

            $this->saveDetailedHistoryItem(
                $incomes[$i], $mashine->address_id, $mashine->imei_id, $mashine->id, $startTimestamp, $endTimestamp
            );
        }

        return $incomes;
    }

    /**
     * Gets total number of mashines, pertaining company
     *
     * @return decimal
     */ 
    public function getTotalMashinesCount()
    {
        $entity = new Entity();

        return WmMashine::find()->where(['company_id' => $entity->getCompanyId()])->count();
    }

    /**
     * Save detailed history item to `j_summary` table
     *
     * @param array $incomes
     * @param int $addressId
     * @param int $imeiId
     * @param int $mashineId
     * @param timestamp $start
     * @param timestamp $end
     */ 
    public function saveDetailedHistoryItem($incomes, $addressId, $imeiId, $mashineId, $start, $end)
    {
        $jSummary = new Jsummary();
        $stepInterval = 3600 * 24;

        $incomeByMashines = 
                '`'
                .$mashineId.'**'
                .$incomes['created'].'**'
                .$incomes['deleted'].'**'
                .$incomes['income'].'**'
                .$incomes['idleHours'].'**'
                .$incomes['allHours'].'**'
                .$incomes['encashment_date'].'**'
                .$incomes['encashment_sum']
                .'`';
        $jSummary->saveItemDetailed($imeiId, $addressId, $start,  $end, [], $incomeByMashines);
    }

    /**
     * Makes events string 
     *
     * @param array $incomeData
     * @return string
     */ 
    public function getEventsAsString($incomeData)
    {
        $eventsString = '';

        if (!empty($incomeData['created'])) {
            $eventsString .= Yii::t('frontend', 'Addition').', ';
        }

        if (!empty($incomeData['deleted'])) {
            $eventsString .= Yii::t('frontend', 'Deletion').', ';
        }

        if (!empty($incomeData['encashment_date'])) {
            $eventsString .= Yii::t('frontend', 'Encashment').', ';
        }

        $eventsString = trim($eventsString);

        if (!empty($eventsString)) {
            $eventsString = mb_substr($eventsString, 0, mb_strlen($eventsString) - 1);
        }

        return $eventsString;
    }
}