<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\services\globals\Entity;
use frontend\services\custom\Debugger;
use frontend\services\globals\QueryOptimizer;
use frontend\services\globals\DateTimeHelper;
use Yii;

/**
 * This is the model class for table "j_summary".
 *
 * @property integer $id
 * @property integer $imei_id
 * @property timestamp $start_timestamp
 * @property timestamp $end_timestamp
 * @property double $income
 * @property integer $created
 * @property integer $active
 * @property integer $deleted
 * @property integer $all
 * @property integer $idleHours
 * @property text $income_by_mashines
 */
class Jsummary extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j_summary';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei_id', 'start_timestamp', 'end_timestamp'], 'required'],
            [['imei_id', 'created', 'active', 'deleted', 'all', 'encashment_date'], 'integer'],
            [['income', 'idleHours', 'allHours', 'damageIdleHours', 'encashment_sum'] , 'double'],
            [['idleHoursReasons'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'imei_id' => Yii::t('frontend', 'Imei ID'),
            'start_timestamp' => Yii::t('frontend', 'Start Timestamp'),
            'end_timestamp' => Yii::t('frontend', 'End Timestamp'),
            'income' => Yii::t('frontend', 'Income'),
            'created' => Yii::t('frontend', 'Created'),
            'active' => Yii::t('frontend', 'Active'),
            'deleted' => Yii::t('frontend', 'Deleted'),
            'all' => Yii::t('frontend', 'All'),
            'idleHours' => Yii::t('frontend', 'Idle Hours'),
            'income_by_mashines' => Yii::t('frontend', 'Income By Mashines')
        ];
    }

    /**
     * Gets income string or array by mashine id
     *
     * @param string $incomeByMashine
     * @param int $mashineId
     * @param bool $isString
     * @return array
     */
    private function getIncomeStringByMashine($incomeByMashines, $mashineId, $isString)
    {
        $index = strrpos($incomeByMashines, '`'.$mashineId.'**');
        if ($index !== FALSE) {
            $subStr = substr($incomeByMashines, $index);
            $length = strpos(substr($subStr, 1), '`') + 2;

            if ($isString) {

                return substr($incomeByMashines, $index, $length);
            }

            return ['index' => $index, 'length' => $length];
        }

        return false;
    }

    /**
     * Parses income string for detailed summary
     *
     * @param string $incomeString
     * @return array
     */
    public function parseIncomeString($incomeString)
    {
        $parts = explode('**', $incomeString);
        $created = isset($parts[1]) ? $parts[1] : false;
        $deleted = isset($parts[2]) ? $parts[2] : false;
        $income = isset($parts[3]) ? $parts[3] : null;
        $idleHours = isset($parts[4]) ? $parts[4] : null;
        $allHours = isset($parts[5]) ? $parts[5] : null;
        $encashment_date = isset($parts[6]) ? $parts[6] : null;
        $encashment_sum = isset($parts[7]) ? explode("`", $parts[7])[0] : null;
        $damageIdleHours = isset($parts[8]) ? explode("`", $parts[8])[0] : null;
        $workIdleHours = isset($parts[9]) ? explode("`", $parts[9])[0] : null;
        $connectIdleHours = isset($parts[10]) ? explode("`", $parts[10])[0] : null;
        $cbConnectionIdleHours = isset($parts[11]) ? explode("`", $parts[11])[0] : null;
        $cbIdleHours = isset($parts[12]) ? explode("`", $parts[12])[0] : null;
        $idleHoursReasons = $workIdleHours.'**'.$connectIdleHours.'**'.$cbConnectionIdleHours.'**'.$cbIdleHours;

        return [
            'created' => $created,
            'deleted' => $deleted,
            'income' => $income,
            'idleHours' => $idleHours,
            'damageIdleHours' => $damageIdleHours,
            'idleHoursReasons' => $idleHoursReasons,
            'allHours' => $allHours,
            'encashment_date' => $encashment_date,
            'encashment_sum' => $encashment_sum
        ];
    }

    /**
     * Save item by imei id and timestamps
     * 
     * @param int $imei_id
     * @param int $address_id
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param array $params
     * @return int
     */
    public function saveItem($imei_id, $address_id, $startTimestamp, $endTimestamp, $params)
    {
        $itemQuery = Jsummary::find()->andWhere(
            ['address_id' => $address_id, 'imei_id' => $imei_id, 'start_timestamp' => $startTimestamp, 'end_timestamp' => $endTimestamp]
        );

        if (!$itemQuery->count()) {
            $item = new Jsummary();
            $item->imei_id = $imei_id;
            $item->address_id = $address_id;
            $item->start_timestamp = $startTimestamp;
            $item->end_timestamp = $endTimestamp;
            $item->attributes = $params;
            $item->save(false);
        } else {
            $item = QueryOptimizer::getItemByQuery($itemQuery->limit(1));
            $item->attributes = $params;
            $item->update(false);
        }

        return $item->id;
    }

    /**
     * Saves detailed item by imei id, address_id, params, mashine incomes and timestamps
     *
     * @param int $imei_id
     * @param int $address_id
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param timestamp $params
     * @param string $incomeByMashines
     * @return int
     */
    public function saveItemDetailed($imei_id, $address_id, $startTimestamp, $endTimestamp, $params, $incomeByMashines)
    {
        $item = $this->getItemFromGlobal($address_id, $imei_id, $startTimestamp, $endTimestamp);

        if (empty($item)) {
            $item = new Jsummary();
            $item->imei_id = $imei_id;
            $item->address_id = $address_id;
            $item->start_timestamp = $startTimestamp;
            $item->end_timestamp = $endTimestamp;
        }

        if ($incomeByMashines) {
            if (!empty($item->income_by_mashines)) {
                $mashineId = explode('**', $incomeByMashines)[0];
                $mashineId = substr($mashineId, 1);
                $incomeParts = $this->getIncomeStringByMashine($item->income_by_mashines, $mashineId, false);
                if ($incomeParts) {
                    $item->income_by_mashines = 
                        substr($item->income_by_mashines, 0, $incomeParts['index']).
                        substr($item->income_by_mashines, $incomeParts['index'] + $incomeParts['length']);
                }

                $item->income_by_mashines .= $incomeByMashines;
            } else {
                $item->income_by_mashines = $incomeByMashines;
            }
        }

        $item->save(false);

        return $item->id;
    }

    /**
     * Gets incomes by imei id, address_id and timestamps
     *
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param timestamp $todayTimestamp
     * @param int $address_id
     * @param int $imei_id
     * @return array
     */
    public function getIncomes($startTimestamp, $endTimestamp, $todayTimestamp, $address_id, $imei_id = false)
    {
        $stepInterval = 3600*24;
        $items = $this->getQueryItemsFromGlobal($startTimestamp, $endTimestamp, $todayTimestamp, $address_id, $imei_id);
        $incomes = [];

        for ($i = 0; $i < count($items); ++$i) {
            $item = $items[$i];
            $imei = $this->getImeiFromGlobalById($item->imei_id);
            $day = floor(($item->start_timestamp - $startTimestamp) / $stepInterval + 1);
            if (!is_null($item->idleHours)) {

                if (!empty($item->is_cancelled)) {
                    $incomes[$day] = [
                        'income' => null,
                        'created' => 0,
                        'active' => 0,
                        'deleted' => 0,
                        'all' => 0,
                        'idleHours' => null,
                        'damageIdleHours' => null,
                        'idleHoursReasons' => null,
                        'allHours' => null,
                        'encashment_date' => null,
                        'encashment_sum' => null,
                        'imei' => !empty($imei) ? $imei->imei : Yii::t('frontend', 'Undefined'),
                        'imei_id' => !empty($imei) ? $imei->id : 0,
                        'address_id' => $address_id,
                        'is_cancelled' => $item->is_cancelled,
                        'income_by_mashines' => null,
                        'id' => $item->id,
                        'full_income_by_mashines' => $item->income_by_mashines
                    ];
                } else {
                    $incomes[$day] = [
                        'income' => $item->income,
                        'created' => $item->created,
                        'deleted' => $item->deleted,
                        'active' => $item->active,
                        'all'=> $item->all,
                        'idleHours' => $item->idleHours,
                        'damageIdleHours' => $item->damageIdleHours,
                        'idleHoursReasons' => $item->idleHoursReasons,
                        'allHours' => $item->allHours,
                        'encashment_date' => $item->encashment_date,
                        'encashment_sum' => $item->encashment_sum,
                        'imei' => !empty($imei) ? $imei->imei : Yii::t('frontend', 'Undefined'),
                        'imei_id' => !empty($imei) ? $imei->id : 0,
                        'address_id' => $address_id,
                        'is_cancelled' => $item->is_cancelled,
                        'income_by_mashines' => $item->income_by_mashines,
                        'id' => $item->id,
                        'full_income_by_mashines' => $item->income_by_mashines
                    ];
                }
            }
        }

        return $incomes;
    }

    /**
     * Gets incomes for detailed summary
     *
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param timestamp $todayTimestamp
     * @param WmMashine $mashine
     * @return array
     */
    public function getDetailedIncomes($startTimestamp, $endTimestamp, $todayTimestamp, $mashine)
    {
        $incomes = $this->getIncomes($startTimestamp, $endTimestamp, $todayTimestamp, $mashine->address_id, $mashine->imei_id);

        $detailedIncomes = [];
        foreach ($incomes as $day => $income) {

            $fullIncomeByMashines = $income['full_income_by_mashines'];
            $incomeByMashines = $income['income_by_mashines'];
            $fullIncomeString = $this->getIncomeStringByMashine($fullIncomeByMashines, $mashine->id, true);
            $incomeString = $this->getIncomeStringByMashine($incomeByMashines, $mashine->id, true);

            // if is cancelled or data by mashine id exists then make income
            if (!empty($fullIncomeString) || !empty($income['is_cancelled'])) {
                $detailedIncomes[$day] = array_merge(
                    $this->parseIncomeString($incomeString),
                    [
                        'is_cancelled' => $income['is_cancelled'],
                        'imei' => $income['imei'],
                        'imei_id' => $income['imei_id'],
                        'address_id' => $income['address_id'],
                        'mashine_id' => $mashine->id
                    ]
                );
            }
        }

        return $detailedIncomes;
    }

    /**
     * Gets total income by timestamps
     *
     * @param timestamp $start
     * @param timestamp $end
     * @return array
     */
    public function getTotalIncomeByTimestamps($start, $end)
    {
        $income = Jsummary::find()->select('imei_id, income')
                                  ->distinct()
                                  ->andWhere(['>=', 'start_timestamp', $start])
                                  ->andWhere(['<=', 'end_timestamp', $end + 1])
                                  ->sum('income');

        return $income;
    }

    /**
     * Sets record cancellation by params (timestamps, imei_id, address_id)
     *
     * @param array $params
     * @return boolean
     */
    public function setIncomeCancellation($params)
    {
        $keys = ['addressId', 'imeiId', 'start', 'end', 'isCancelled'];
        if (count(array_diff($keys, array_keys($params)))) {

            return false;
        }

        $query = Jsummary::find()->andWhere([
            'address_id' => $params['addressId'],
            'imei_id' => $params['imeiId'],
            'start_timestamp' => $params['start'],
            'end_timestamp' => $params['end']
        ]);

        foreach ($query->all() as $item) {
            $item->is_cancelled = empty($params['isCancelled']) ? true : false;
            $item->save(false);
        }

        return true;
    }

    /**
     * Gets imei from global variable by id
     *
     * @param array $id
     * @return Imei|null
     */    
    public function getImeiFromGlobalById($id)
    {

        $query = Imei::find()->where(['id' => $id]);

        return QueryOptimizer::getItemByQuery($query);
    }

    /**
     * Gets jsummary item from global variable
     *
     * @param int $address_id
     * @param int $imei_id
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @return Jsummary|null
     */
    public function getItemFromGlobal($address_id, $imei_id, $startTimestamp, $endTimestamp)
    {

        $query = Jsummary::find()->andWhere([
            'address_id' => $address_id,
            'imei_id' => $imei_id,
            'start_timestamp' => $startTimestamp,
            'end_timestamp' => $endTimestamp
        ]);

        return QueryOptimizer::getItemByQuery($query->limit(1));
    }

    /**
     * Gets jsummary items from global variable
     *
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param timestamp $todayTimestamp
     * @param int $address_id
     * @param int $imei_id
     * @return array
     */
    public function getQueryItemsFromGlobal($startTimestamp, $endTimestamp, $todayTimestamp, $address_id, $imei_id = false)
    {
        $itemsQuery = Jsummary::find()->andWhere(['address_id' => $address_id])
                                      ->andWhere(['>=', 'start_timestamp', $startTimestamp])
                                      ->andWhere(['<', 'start_timestamp', $todayTimestamp])
                                      ->andWhere(['<=', 'end_timestamp', $endTimestamp]);

        if (!empty($imei_id)) {
            $itemsQuery = $itemsQuery->andWhere(['imei_id' => $imei_id]);
        }

        $itemsQuery->orderBy(['start_timestamp' => SORT_ASC]);

        return QueryOptimizer::getItemsByQuery($itemsQuery);
    }

    /**
     * Gets address from global variable by id
     *
     * @param array $id
     * @return AddressBalanceHolder|null
     */    
    public function getAddressFromGlobalById($id)
    {
        $query = AddressBalanceHolder::find()->andWhere(['id' => $id])->limit(1);

        return QueryOptimizer::getItemByQuery($query);
    }

    /**
     * Gets income by address id and timestamps
     *
     * @param int $start
     * @param int $end
     * @return int $addressId
     */
    public function getIncomeByAddressIdAndTimestamps($start, $end, $addressId)
    {
        $dateTimeHelper = new DateTimeHelper();
        $incomeTotal = 0;
        $todayTimestamp = $dateTimeHelper->getRealUnixTimeOffset(0);
        $todayTimestamp = $dateTimeHelper->getDayBeginningTimestamp($todayTimestamp);
        $month = date('m', $start);
        $year = date('Y', $start);
        $addressQuery = AddressBalanceHolder::find()->where(['id' => $addressId])->limit(1);
        $address = QueryOptimizer::getItemByQuery($addressQuery);

        $addressImeiData = new AddressImeiData();

        $nextMonthBeginning = $dateTimeHelper->getNextMonthBeginningByTimestamp($start);
        $wmMashinesCount = $addressImeiData->getWmMashinesCountByYearMonth($year, $month, $address);

        if ($nextMonthBeginning < $end) {
            $incomeNextMonth = $this->getIncomeByAddressIdAndTimestamps($nextMonthBeginning, $end, $addressId);
            $incomeThisMonth = $this->getIncomeByAddressIdAndTimestamps($start, $nextMonthBeginning, $addressId);

            return $incomeThisMonth + $incomeNextMonth;
        } elseif ($wmMashinesCount == 0) {

            return 0;
        }

        $incomes = $this->getIncomes($start, $end, $todayTimestamp, $addressId, false);

        foreach ($incomes as $day => $income) {
            $incomeTotal += $income['income'] ?? 0;
        }

        return $incomeTotal;
    }
}
