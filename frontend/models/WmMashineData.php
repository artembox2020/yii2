<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "wm_mashine_data".
 *
 * @property int $id
 * @property int $mashine_id
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
 * @property int $current_status
 *
 * @property WmMashine $wmMashine
 */
class WmMashineData extends \yii\db\ActiveRecord
{
    /** @var array $current_state */
    public $current_state = [
        '-2' => 'nulling',
        '-1' => 'refill',
        'disconnected',
        'idle',
        'power on',
        'busy',
        'washing',
        'rising',
        'extraction',
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wm_mashine_data';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_deleted' => true,
                    'deleted_at' => time()
                ],
            ],
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mashine_id'], 'required'],
            [['mashine_id', 'number_device',
                'level_signal', 'bill_cash',
                'door_position', 'door_block_led',
                'status',
                'current_status',
                'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type_mashine'], 'string', 'max' => 255],
            [['mashine_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmMashine::className(), 'targetAttribute' => ['mashine_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'mashine_id' => Yii::t('frontend', 'Wm Mashine ID'),
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
            'current_status' => Yii::t('frontend', 'Current Status')
        ];
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmMashine()
    {
        return $this->hasOne(WmMashine::className(), ['id' => 'mashine_id']);
    }
}
