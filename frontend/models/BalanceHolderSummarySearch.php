<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\BalanceHolder;
use frontend\models\ImeiData;
use frontend\models\Jlog;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use yii\helpers\ArrayHelper;

/**
 * BalanceHolderSummarySearch represents the model to make summary journal
 */
class BalanceHolderSummarySearch extends BalanceHolder
{

    const PERCENT_ONE_THIRD = 33;
    const PERCENT_TWO_THIRD = 67;
    const IDLE_TIME_HOURS = 8;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'created_at', 'deleted_at'], 'integer'],
            [['name', 'city', 'address', 'phone', 'contact_person', 'is_deleted'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function baseSearch($params)
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new BalanceHolder());
        
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied and restricts to one item
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function limitOneBaseSearch($params)
    {
        $dataProvider = $this->baseSearch($params);
        $dataProvider->query = $dataProvider->query->limit(1);
        $dataProvider->pagination = false;

        return $dataProvider;
    }

    /**
     * Gets the total number of all addresses
     *
     * @return int
     */
    public static function getTotalAddressesCount()
    {
        $entity = new Entity();

        return AddressBalanceHolder::find()->where(['company_id' => $entity->getCompanyId()])->count();
    }

    /**
     * Gets income by address, year, month and day
     *
     * @param int $address_id
     * @param int $year
     * @param int $month
     * @param int $day
     * @return decimal
     */
    public function getIncomeByYearMonthDay($address_id, $year, $month, $day)
    {
        $timestampStart = $this->getTimestampByYearMonthDay($year, $month, $day, true);
        $timestampEnd = $this->getTimestampByYearMonthDay($year, $month, $day, false);
        $income = $this->getIncomeByAddressId($address_id, $timestampStart, $timestampEnd);

        return $income;
    }

    /**
     * Gets timestamp by year, month, day
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param boolean $isBeginning
     *
     * @return int
     */
    public function getTimestampByYearMonthDay($year, $month, $day, $isBeginning)
    {
        if ($isBeginning) {

            return  strtotime($year.'-'.$month.'-'.$day.' 00:00:00') + 0;
        } else {

            return  strtotime($year.'-'.$month.'-'.$day.' 23:59:59') + 0;
        }
    }

    /**
     * Gets timestamp referring to the beginning(end) of the day
     *
     * @param int $timestamp
     * @return timestamp
     */
    public function getDayBeginningTimestampByTimestamp($timestamp)
    {
        $year = date('Y', $timestamp);
        $month = date('m', $timestamp);
        $day = date('d', $timestamp);

        return  strtotime($year.'-'.$month.'-'.$day.' 00:00:00');
    }

    /**
     * Gets the next month timestamp
     *
     * @return timestamp
     */
    public function getNextMonthBeginningTimestamp()
    {
        $timestamp = time() + Jlog::TYPE_TIME_OFFSET;
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);
        if ($month == '12') {
            $month = '01';
            ++$year;
        } else {
            ++$month;
        }

        return strtotime($year.'-'.$month.'-01 00:00:00');
    }

    /**
     * Gets timestamp by year and month
     *
     * @param int $year
     * @param int $month
     *
     * @return timestamp
     */
    public function getTimestampByYearMonth($year, $month)
    {
        $days = $this->getDaysByMonths($year)[$month];
        $timestampStart = $this->getTimestampByYearMonthDay($year, $month, '01', true);
        $timestampEnd = $this->getTimestampByYearMonthDay($year, $month, $days, false);

        return [
            'start' => $timestampStart,
            'end' => $timestampEnd
        ];
    }

    /**
     * Gets the year of the last month
     *
     * @return int
     */   
    public function getYearLastMonth() {
        $timestamp = time() + Jlog::TYPE_TIME_OFFSET;
        $year = date('Y', $timestamp);
        if (date('m', $timestamp) == '01') {
            --$year;
        }

        return $year;
    }

    /**
     * Gets the the last month
     *
     * @return int
     */  
    public function getLastMonth() {
        $timestamp = time()+ Jlog::TYPE_TIME_OFFSET;
        $month = date('m', $timestamp);
        if ($month == '01') {
            $month = '12';
        } else {
            --$month;
            if ($month < 10) {
                $month = '0'.$month;
            }
        }

        return $month;
    }

    /**
     * Gets array, representing months
     *
     * @return array
     */  
    public function getMonths()
    {

        return [
            '01' => Yii::t('common', 'January'),
            '02' => Yii::t('common', 'February'),
            '03' => Yii::t('common', 'March'),
            '04' => Yii::t('common', 'April'),
            '05' => Yii::t('common', 'May'),
            '06' => Yii::t('common', 'June'),
            '07' => Yii::t('common', 'July'),
            '08' => Yii::t('common', 'August'),
            '09' => Yii::t('common', 'September'),
            '10' => Yii::t('common', 'October'),
            '11' => Yii::t('common', 'November'),
            '12' => Yii::t('common', 'December'),
        ];
    }

    /**
     * Gets array of the years
     *
     * @return array
     */  
    public function getYears()
    {
        $currentYear = (int)date('Y');
        $years = [];
        for ($i = $currentYear - 5; $i <= $currentYear + 1; ++$i) {
            $years[$i] = $i;
        }

        return $years;
    }

    /**
     * Check whether the year is leap
     *
     * @return boolean
     */  
    private function is_leap_year($year) {
	    return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0)));
    }

    /**
     * Gets array representing months and number of days
     *
     * @param $year
     * @return array
     */  
    public function getDaysByMonths($year)
    {
        
        return [
            '01' => 31,
            '02' => $this->is_leap_year($year) ? 29 : 28,
            '03' => 31,
            '04' => 30,
            '05' => 31,
            '06' => 30,
            '07' => 31,
            '08' => 31,
            '09' => 30,
            '10' => 31,
            '11' => 30,
            '12' => 31
        ];
    }

    /**
     * Sets params if necessary
     *
     * @return array
     */  
    public function setParams($params)
    {
        $timestamp = time() + Jlog::TYPE_TIME_OFFSET;
        $params['month'] = $params['month'] ? $params['month'] : date('m', $timestamp);
        $params['year'] = $params['year'] ? $params['year'] : date('Y', $timestamp);

        return $params;
    }

    /**
     * Gets the query representing all active mashines
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param int $imeiId
     * @return ActiveDbQuery
     */  
    public function getAllActiveMashinesQueryByTimestamps($start, $end, $imeiId)
    {
        $query = WmMashineData::find()->select('mashine_id')->distinct()->andWhere(['>=', 'created_at', $start]);
        $query = $query->andWhere(['<=', 'created_at', $end]);
        $mashineIds = ArrayHelper::getColumn($query->all(), 'mashine_id');
        $query = WmMashine::find()->select('id')->distinct()->andWhere(['imei_id' => $imeiId, 'id' => $mashineIds]);

        return $query;
    }
    
    /**
     * Gets the query representing all mashines
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param int $imeiId
     * @return ActiveDbQuery
     */ 
    public function getAllMashinesQueryByTimestamps($start, $end, $imeiId)
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new WmMashine());
        $query = $query->where(['imei_id' => $imeiId, 'company_id' => $entity->getCompanyId()]);
        $query = $query->andWhere(['<=', 'created_at', $end]);
        $query = $query->andWhere(new \yii\db\conditions\OrCondition([
                            new \yii\db\conditions\AndCondition([
                              ['=', 'wm_mashine.is_deleted', false],
                            ]),
                            new \yii\db\conditions\AndCondition([
                              ['=', 'wm_mashine.is_deleted', true],
                              ['>', 'wm_mashine.deleted_at', $start]
                            ])
                        ]));

        return $query;
    }

    /**
     * Gets the query representing all imeis
     *
     * @param timestamp $start
     * @param timestamp $end
     * @return ActiveDbQuery
     */ 
    public function getAllImeisQueryByTimestamps($start, $end)
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new Imei());
        $query = $query->where(['company_id' => $entity->getCompanyId()]);
        $query = $query->andWhere(['<=', 'created_at', $end]);
        $query = $query->andWhere(new \yii\db\conditions\OrCondition([
                            new \yii\db\conditions\AndCondition([
                              ['=', 'imei.is_deleted', false],
                            ]),
                            new \yii\db\conditions\AndCondition([
                              ['=', 'imei.is_deleted', true],
                              ['>', 'imei.deleted_at', $start]
                            ])
                        ]));

        return $query;
    }

    /**
     * Gets the query representing all deleted mashines
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param int $imeiId
     * @return ActiveDbQuery
     */ 
    public function getAllDeletedMashinesQueryByTimestamps($start, $end, $imeiId)
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new WmMashine());
        $query = $query->where(['imei_id' => $imeiId, 'company_id' => $entity->getCompanyId(), 'is_deleted' => true]);
        $query = $query->andWhere(['>=', 'deleted_at', $start]);
        $query = $query->andWhere(['<=', 'deleted_at', $end]);

        return $query;
    }

    /**
     * Gets the query representing all added mashines
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param int $imeiId
     * @return ActiveDbQuery
     */ 
    public function getAllAddedMashinesQueryByTimestamps($start, $end, $imeiId)
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new WmMashine());
        $query = $query->where(['imei_id' => $imeiId, 'company_id' => $entity->getCompanyId()]);
        $query = $query->andWhere(['>=', 'created_at', $start]);
        $query = $query->andWhere(['<=', 'created_at', $end]);

        return $query;
    }

    /**
     * Gets the query representing all mashines by year, month and address
     *
     * @param int $year
     * @param int $month
     * @param ActiveRecord $address
     * @return ActiveDbQuery
     */ 
    public function getAllMashinesQueryByYearMonth($year, $month, $address)
    {
        $entity = new Entity(); 
        $imei = Imei::find()->andWhere(
            ['address_id' => $address->id, 'status' => $address->status, 'company_id' => $entity->getCompanyId()]
        );
        $imei = $imei->limit(1)->one();

        if ($imei) {

            $timestamps = $this->getTimestampByYearMonth($year, $month);
            $query = $this->getAllMashinesQueryByTimestamps($timestamps['start'], $timestamps['end'], $imei->id);

            return $query;
        } else {

            return WmMashine::find()->where('0');
        }
    }

    /**
     * Gets the  base query representing data from ImeiData 
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param Imei $imei
     * @param string $selectString
     * @return ActiveDbQuery
     */ 
    public function getBaseQueryByImeiAndTimestamps($start, $end, $imei, $selectString)
    {
        $baseQuery = ImeiData::find()->select($selectString)
                                     ->andWhere(['imei_id' => $imei->id])
                                     ->andWhere(['>=', 'created_at', $start])
                                     ->andWhere(['<', 'created_at', $end]);

        return $baseQuery;
    }

    /**
     * Gets income by imei and timestamps
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param Imei $imei
     * @return decimal
     */ 
    public function getIncomeByImeiAndTimestamps($start, $end, $imei)
    {
        $jSummary = new Jsummary();
        $todayTimestamp = $this->getDayBeginningTimestampByTimestamp(time() + Jlog::TYPE_TIME_OFFSET);

        if ($todayTimestamp >= $end && $jSummaryItem = $jSummary->getItem($imei->id, $start, $end)) {

            return $jSummaryItem->income;
        }

        $selectString = 'fireproof_residue, created_at, imei_id';
        $query = $this->getBaseQueryByImeiAndTimestamps($start, $end, $imei, $selectString);
        $queryS1 = $query->orderBy(['created_at' => SORT_ASC]);
        $itemStart = $queryS1->limit(1)->one();
        if (!empty($itemStart)) {
            $entityHelper = new EntityHelper();

            // makes none-zero time intervals
            $nonZeroIntervals = $entityHelper->makeNonZeroIntervalsByTimestamps(
                $start,
                $end,
                new ImeiData(),
                $imei,
                'imei_id',
                $selectString,
                'fireproof_residue'
            );
            $income = 0;
            $isFirst = true;
            
            //calculation by each non-zero time interval and summing
            foreach ($nonZeroIntervals as $interval) {
                $income += $entityHelper->getUnitIncomeByNonZeroTimestamps(
                    $interval['start'],
                    $interval['end'],
                    new ImeiData(),
                    $imei,
                    'imei_id',
                    $selectString,
                    'fireproof_residue',
                    $isFirst
                );
                $isFirst = false;
            }
            $income = $this->parseFloat($income, 2);
        } else {
            $income = null;
        }

        $jSummary->saveItem($imei->id, $start, $end, ['income' => $income]);

        return $income;
    }

    /**
     * Gets income by the last year and month
     *
     * @param int year
     * @param int $month
     * @return decimal
     */ 
    public function getIncomeForLastYear($year, $month)
    {
        $year = $this->parseFloat($year, 2);
        --$year;
        $numberOfDays = $this->getDaysByMonths($year)[$month];
        $timestampStart = strtotime($year.'-'.$month.'-01 00:00:00');
        $timestampEnd = strtotime($year.'-'.$month.'-'.$numberOfDays.' 23:59:59');
        $imeis = $this->getAllImeisQueryByTimestamps($timestampStart, $timestampEnd);
        $income = 0;

        foreach ($imeis->all() as $imei) {
            $income += $this->parseFloat($this->getIncomeByImeiAndTimestamps($timestampStart, $timestampEnd + 1, $imei), 2);
        }

        return $income;
    }

    /**
     * Gets the number of idle hours by imei and timestamps
     *
     * @param timestamp $start
     * @param timestamp $end
     * @param Imei $imei
     * @return decimal
     */ 
    public function getIdleHoursByImeiAndTimestamps($start, $end, $imei)
    {
        $stepInterval = self::IDLE_TIME_HOURS * 3600;
        $idleHours = 0.00;
        $endTimestamp = $start + $stepInterval;
        $selectString = 'created_at, imei_id';
        for ($timestamp = $start; $endTimestamp <= $end; $timestamp += $stepInterval, $endTimestamp = $timestamp + $stepInterval) {
            $query = $this->getBaseQueryByImeiAndTimestamps($timestamp, $endTimestamp, $imei, $selectString);
            if ($query->count() > 0) {
                $item = $query->orderBy(['created_at' => SORT_DESC])->limit(1)->one();
                $timestamp = $item->created_at - $stepInterval + 1;
                continue;
            } else {
                $query = $this->getBaseQueryByImeiAndTimestamps($endTimestamp, $end, $imei, $selectString);
                if ($query->count() == 0) {
                    $idleHours += $this->parseFloat(((float)$end - $timestamp) / 3600, 2);
                    break;
                } else {
                    $item = $query->orderBy(['created_at' => SORT_ASC])->limit(1)->one();
                    $timeDiff = $item->created_at - $endTimestamp;
                    $idleHours += self::IDLE_TIME_HOURS + $this->parseFloat((float)$timeDiff / 3600, 2);
                    $timestamp = $item->created_at - $stepInterval + 1;
                    continue;
                }
            }
        }

        return $idleHours;
    }

    /**
     * Gets info about mashines by imei and timestamps
     *
     * @return array
     */ 
    public function getMashineStatisticsByImeiAndTimestamps($timestamp, $timestampEnd, $imei)
    {
        $jSummary = new Jsummary();
        $todayTimestamp = $this->getDayBeginningTimestampByTimestamp(time() + Jlog::TYPE_TIME_OFFSET);

        if (
            $todayTimestamp >= $timestampEnd 
            && ($jSummaryItem = $jSummary->getItem($imei->id, $timestamp, $timestampEnd))
            && !is_null($jSummaryItem->created)
        ) {
            list($mashinesCreated, $mashinesDeleted, $mashinesActive, $mashinesAll, $idleHours) = [
                $jSummaryItem->created, $jSummaryItem->deleted,
                $jSummaryItem->active, $jSummaryItem->all, $jSummaryItem->idleHours
            ];
            $needToSave = false;
        } else {
            $mashinesCreated = $this->getAllAddedMashinesQueryByTimestamps($timestamp, $timestampEnd, $imei->id)->count();
            $mashinesDeleted = $this->getAllDeletedMashinesQueryByTimestamps($timestamp, $timestampEnd, $imei->id)->count();
            $mashinesActive = $this->getAllActiveMashinesQueryByTimestamps($timestamp, $timestampEnd, $imei->id)->count();
            $mashinesAll = $this->getAllMashinesQueryByTimestamps($timestamp, $timestampEnd, $imei->id)->count();
            $idleHours = $this->getIdleHoursByImeiAndTimestamps($timestamp, $timestampEnd, $imei);
            $needToSave = true;
        }

        $mashineStatistics = [
            'created' => $mashinesCreated,
            'deleted' => $mashinesDeleted,
            'active' => $mashinesActive,
            'all' => $mashinesAll,
            'idleHours' => $idleHours
        ];

        if ($needToSave) {
            $jSummary->saveItem($imei->id, $timestamp, $timestampEnd, $mashineStatistics);
        }

        return $mashineStatistics;
    }

    /**
     * Gets incomes by year, month and address
     *
     * @param int $year
     * @param int $month
     * @param AddressBalanceHolder $address
     * @return array
     */
    public function getIncomesByYearMonth($year, $month, $address)
    {
        $entity = new Entity();
        $jSummary = new Jsummary();
        $imeiQuery = $entity->getUnitsQueryPertainCompany(new Imei());
        $imei = $imeiQuery->andWhere(['address_id' => $address->id, 'status' => $address->status])->limit(1)->one();
        $incomes = [];
        $timestamps = $this->getTimestampByYearMonth($year, $month);

        if ($imei) {
            $totalNumberOfMashines = $this->getAllMashinesQueryByTimestamps(
                $timestamps['start'], $timestamps['end'], $imei->id
            );
            $totalNumberOfMashines = $totalNumberOfMashines->count();
        }

        $beginningTimestamp = $this->getDayBeginningTimestampByTimestamp($address->created_at);
        $todayTimestamp = $this->getDayBeginningTimestampByTimestamp(time() + Jlog::TYPE_TIME_OFFSET);
        $nextMonthTimestamp = $this->getNextMonthBeginningTimestamp();
        $numberOfDays = $this->getDaysByMonths($year)[$month];
        if ($imei && $totalNumberOfMashines > 0) {
            $intervalStep = 3600 * 24;
            $incomesFromHistory = $jSummary->getIncomes($timestamps['start'], $timestamps['end'] + 1, $todayTimestamp, $imei->id);
            $daysArray = [];

            for ($k = 1; $k <= $numberOfDays; ++$k) {
                $daysArray[$k] = $k;
            }
            $emptyDays = array_diff($daysArray, array_keys($incomesFromHistory));
            $needToBreak = false;
            foreach ($emptyDays as $day)
            {
                $timestamp = $timestamps['start'] + ($day - 1) *$intervalStep; 

                if ($todayTimestamp < $timestamp) {
                    break;
                }

                $timestampDiff = $timestamp - $beginningTimestamp;
                $numberOfMashines = $this->getAllMashinesQueryByTimestamps(
                    $timestamp, $timestamp + $intervalStep - 1, $imei->id
                );
                $numberOfMashines = $numberOfMashines->count();

                if ($timestampDiff < 0 || $numberOfMashines == 0) {
                    continue;
                }

                $income = $this->getIncomeByImeiAndTimestamps($timestamp, $timestamp + $intervalStep, $imei);

                if (
                    is_null($income) &&
                    (($timestamp == $nextMonthTimestamp) || ($timestamp == $todayTimestamp && $day == 1))
                )
                {
                    $income = $this->getAverageIncomeByLastMonth($address);
                    $needToBreak = true;
                }

                $timestampEnd = $timestamp + $intervalStep;
                $mashineStatistics = $this->getMashineStatisticsByImeiAndTimestamps($timestamp, $timestampEnd, $imei);
                $incomes[$day] = array_merge(['income' => $income], $mashineStatistics);

                if ($needToBreak) {
                    break;
                }
            }

            $incomes = $incomes + $incomesFromHistory;
        }

        return $incomes;
    }

    /**
     * Gets the average income for the last month
     *
     * @param AddressBalanceHolder $address
     * @return array
     */ 
    public function getAverageIncomeByLastMonth($address)
    {
        $year = $this->getYearLastMonth();
        $month = $this->getLastMonth();
        $incomes = $this->getIncomesByYearMonth($year, $month, $address);
        $totalIncome = 0;
        $totalDays = 0;
        $income = 0;
        $all = 0;
        foreach ($incomes as $day => $income) {

            if (!is_null($income['income'])) {
                $totalIncome += $income['income'];
                ++$totalDays;
            }

            $all = $income['all'];
        }

        if ($totalDays != 0) {
            $income = $totalIncome / $totalDays;
            $income = $this->parseFloat($income, 2);
            
        }

        if (is_array($income)) {
            $income = $income['income'];
        }

        return $income;
    }

    /**
     * Gets class label by incomes
     *
     * @param array $income
     * @return string
     */ 
    public function makeClassByIncome($income)
    {
        if (!empty($income)) {

            if (!isset($income['income']) || is_null($income['income'])) {
                $class = 'not-set-income';
            }
                        
            if (!empty($income['created'])) {
                $class .= ' green-color';
            }
                        
            if (!empty($income['deleted'])) {
                $class .= ' red-color';
            }

            if (isset($income['active'])) {
                $percent = ( $income['all'] - (float)$income['active'] ) / $income['all'] * 100;
                if ($percent <= 1) {
                    $class .= ' white-color';
                } elseif ($percent <= self::PERCENT_ONE_THIRD) {
                    $class .= ' light-grey';
                } elseif ($percent <= self::PERCENT_TWO_THIRD) {
                    $class .= ' middle-grey';
                } elseif ($percent < 100) {
                    $class .= ' heavy-grey';
                } else {
                    $class .= ' dark-grey';
                }
            } elseif (!empty($income['all'])) {
                $class .= ' dark-grey';
            }

            if (!empty($income['idleHours'])) {
                $class .= ' idle';
            }
        }

        return $class;
    }

    /**
     * Gets incomes aggregated data
     *
     * @param ActiveDataProvider $dataProvider
     * @param int $year
     * @param int $month
     * @param int $days
     * @param timestamp $timestamp
     * @return decimal
     */ 
    public function getIncomesAggregatedData($dataProvider, $year, $month, $days, $timestamp)
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
                $incomes = $this->getIncomesByYearMonth($year, $month, $address);
                $countTotal += $this->getAllMashinesQueryByYearMonth($year, $month, $address)->count();
                for ($i = 1, $j = 0; $i <= $days; ++$i) {
                    $summaryTotal[$i] += $this->parseFloat($incomes[$i]['income'], 2);
                    $class = $this->makeClassByIncome($incomes[$i]);
                    $data[$k][$i] = [
                        'timestampStart' => $this->getTimestampByYearMonthDay($year, $month, $i, true),
                        'timestampEnd' => $this->getTimestampByYearMonthDay($year, $month, $i, false),
                        'class' => $class
                    ];
                }
                $data[$k]['incomes'] = $incomes;
                ++$k;
            }
        }
        $data['summaryTotal'] = $summaryTotal;
        $data['countTotal'] = $countTotal;

        return $data;
    }

    /**
     * Parses to decimal
     *
     * @param decimal $number
     * @param int $digits
     * @return decimal
     */ 
    public function parseFloat($number, $digits)
    {
        if ((int)$number != $number) {
            $number = Yii::$app->formatter->asDecimal($number, $digits);
        }

        return $number;
    }
}
