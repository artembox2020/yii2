<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wm_mashine".
 *
 * @property int $id
 * @property int $imei_id
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
 * @property ImeiData $imei
 */
class WmMashine extends \yii\db\ActiveRecord
{
    public $current_status = [
        '-2' => 'nulling',
        '-1' => 'refill',
        'disconnected',
        'idle',
        'power on',
        'busy',
        'washing',
        'rising',
        'extaction',
        'waiting door',
        'end cycle',
        'freeze mode',
        '1e water sensor',
        '3e motor sensor',
        '4e water supply',
        '5e problem plum',
        '8e motor',
        '9e uc poser supply',
        'ae communication',
        'de switch',
        'ce cooling',
        'de unclosed door',
        'fe ventilation',
        'he heater',
        'le water leak',
        'oe of overflow',
        'te temp sensor',
        'ue loading cloth',
        'max error'
    ];

    /**
     * Behaviors TimeStamp
     *
     * @return void
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wm_mashine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei_id'], 'required'],
            [['imei_id', 'number_device', 'level_signal', 'bill_cash', 'door_position', 'door_block_led', 'status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type_mashine'], 'string', 'max' => 255],
            [['is_deleted'], 'string', 'max' => 1],
            [['imei_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImeiData::className(), 'targetAttribute' => ['imei_id' => 'id']],
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
    public function getImei()
    {
        return $this->hasOne(ImeiData::className(), ['id' => 'imei_id']);
    }
}
