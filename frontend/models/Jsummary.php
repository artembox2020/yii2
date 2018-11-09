<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\services\globals\Entity;
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
            [['imei_id', 'created', 'active', 'deleted', 'all'], 'integer'],
            [['income', 'idleHours'] , 'double']
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
            $length = strpos(substr($subStr, 1), '`') - $index + 2;
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
        $isCreated = !is_null($parts[1]) ? $parts[1] : false;
        $isDeleted = !is_null($parts[2]) ? $parts[2] : false;
        $income = !is_null($parts[3]) ? $parts[3] : null;
        $idleHours = !is_null($parts[4]) ? substr($parts[4], 0, -1) : null;

        return [
            'isCreated' => $isCreated,
            'isDeleted' => $isDeleted,
            'income' => $income,
            'idleHours' => $idleHours
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
     * @param bool|string $incomeByMashines
     */
    public function saveItem($imei_id, $address_id, $startTimestamp, $endTimestamp, $params, $incomeByMashines)
    {
        $item = Jsummary::findOne(
            ['address_id' => $address_id, 'imei_id' => $imei_id, 'start_timestamp' => $startTimestamp, 'end_timestamp' => $endTimestamp]
        );

        if (!$item) {
            $item = new Jsummary();
            $item->imei_id = $imei_id;
            $item->address_id = $address_id;
            $item->start_timestamp = $startTimestamp;
            $item->end_timestamp = $endTimestamp;
        }

        $item->attributes = $params;

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
    }

    /**
     * Get item by imei id and timestamps
     *
     * @param int $imei_id
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @return Jsummary
     */
    public function getItem($imei_id, $startTimestamp, $endTimestamp)
    {
        $item = Jsummary::findOne(
            ['imei_id' => $imei_id, 'start_timestamp' => $startTimestamp, 'end_timestamp' => $endTimestamp]
        );

        return $item;
    }

    /**
     * Gets incomes by imei id and timestamps
     *
     * @param timestamp $startTimestamp
     * @param timestamp $endTimestamp
     * @param timestamp $todayTimestamp
     * @param int $imei_id
     * @return array
     */
    public function getIncomes($startTimestamp, $endTimestamp, $todayTimestamp, $address_id, $imei_id)
    {
        $stepInterval = 3600*24;
        $items = [];

        $items = Jsummary::find()->andWhere(['address_id' => $address_id])
                                 ->andWhere(['>=', 'start_timestamp', $startTimestamp])
                                 ->andWhere(['<', 'start_timestamp', $todayTimestamp])
                                 ->andWhere(['<=', 'end_timestamp', $endTimestamp])
                                 ->orderBy(['start_timestamp' => SORT_ASC])
                                 ->all();
        $incomes = [];
        for ($i = 0; $i < count($items); ++$i, ++$day) {
            $item = $items[$i];
            $imei = Imei::find()->where(['id' => $item->imei_id])->one();
            $day = floor(($item->start_timestamp - $startTimestamp) / $stepInterval + 1);
            if (!is_null($item->idleHours)) {
                $incomes[$day] = [
                    'income' => $item->income,
                    'created' => $item->created,
                    'deleted' => $item->deleted,
                    'active' => $item->active,
                    'all'=> $item->all,
                    'idleHours' => $item->idleHours,
                    'imei' => !empty($imei) ? $imei->imei : Yii::t('frontend', 'Undefined'),
                    'income_by_mashines' => $item->income_by_mashines 
                ];
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
            $incomeByMashines = $income['income_by_mashines'];
            $incomeString = $this->getIncomeStringByMashine($incomeByMashines, $mashine->id, true);

            if (!empty($incomeString)) {
                $detailedIncomes[$day] = $this->parseIncomeString($incomeString);
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
}
