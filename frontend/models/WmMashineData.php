<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "wm_mashine_data".
 *
 * @property int $id
 * @property int $wm_mashine_id
 * @property string $type_mashine
 * @property int $number_device
 * @property int $level_signal
 * @property int $bill_cash
 * @property int $door_position
 * @property int $door_block_led
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property WmMashine $wmMashine
 */
class WmMashineData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wm_mashine_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wm_mashine_id'], 'required'],
            [['wm_mashine_id', 'number_device', 'level_signal', 'bill_cash', 'door_position', 'door_block_led', 'status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type_mashine'], 'string', 'max' => 255],
            [['is_deleted'], 'string', 'max' => 1],
            [['wm_mashine_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmMashine::className(), 'targetAttribute' => ['wm_mashine_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'wm_mashine_id' => Yii::t('frontend', 'Wm Mashine ID'),
            'type_mashine' => Yii::t('frontend', 'Type Mashine'),
            'number_device' => Yii::t('frontend', 'Number Device'),
            'level_signal' => Yii::t('frontend', 'Level Signal'),
            'bill_cash' => Yii::t('frontend', 'Bill Cash'),
            'door_position' => Yii::t('frontend', 'Door Position'),
            'door_block_led' => Yii::t('frontend', 'Door Block Led'),
            'status' => Yii::t('frontend', 'Status'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmMashine()
    {
        return $this->hasOne(WmMashine::className(), ['id' => 'wm_mashine_id']);
    }
}