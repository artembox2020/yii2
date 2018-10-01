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
            'idleHours' => Yii::t('frontend', 'Idle Hours')
        ];
    }

    /**
     * Save item by imei id and timestamps
     * 
     */
    public function saveItem($imei_id, $startTimestamp, $endTimestamp, $params)
    {
        $item = Jsummary::findOne(
            ['imei_id' => $imei_id, 'start_timestamp' => $startTimestamp, 'end_timestamp' => $endTimestamp]
        );
        
        if (!$item) {
            $item = new Jsummary();
            $item->imei_id = $imei_id;
            $item->start_timestamp = $startTimestamp;
            $item->end_timestamp = $endTimestamp;
        }
        
        $item->attributes = $params;
        $item->save();
    }

    /**
     * Get item by imei id and timestamps
     *
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
    public function getIncomes($startTimestamp, $endTimestamp, $todayTimestamp, $imei_id)
    {
        $items = Jsummary::find()->andWhere(['imei_id' => $imei_id])
                                 ->andWhere(['>=', 'start_timestamp', $startTimestamp])
                                 ->andWhere(['<', 'start_timestamp', $todayTimestamp])
                                 ->andWhere(['<', 'end_timestamp', $endTimestamp])
                                 ->orderBy(['start_timestamp' => SORT_ASC])
                                 ->all();
        $incomes = [];
        $stepInterval = 3600*24;
        for ($i = 0; $i < count($items); ++$i) {
            $item = $items[$i];
            $day = floor(($item->start_timestamp - $startTimestamp) / $stepInterval + 1);
            $incomes[$day] = [
                'income' => $item->income,
                'created' => $item->created,
                'deleted' => $item->deleted,
                'active' => $item->active,
                'all'=> $item->all,
                'idleHours' => $item->idleHours
            ];
        }

        return $incomes;
    }
}
