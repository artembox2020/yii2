<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "wm_mashine".
 *
 * @property int $id
 * @property int $imei_id
 * @property int $company_id
 * @property int $balance_holder_id
 * @property int $address_id
 * @property string $type_mashine
 * @property int $number_device
 * @property string $serial_number
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
 * @property string $model
 * @property string $brand
 * @property int $date_install
 * @property int $date_build
 * @property int $date_purchase
 * @property int $date_connection_monitoring
 *
 * @property Imei $imei
 */
class WmMashine extends \yii\db\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_UNDER_REPAIR = 2;
    const STATUS_JUNK = 3;

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
            [['imei_id', 'status', 'company_id', 'balance_holder_id', 'address_id'], 'required'],
            [['serial_number'], 'unique'],
            [['imei_id', 'number_device',
                'level_signal',
                'bill_cash',
                'door_position',
                'door_block_led',
                'status',
                'current_status',
                'created_at',
                'updated_at',
                'deleted_at',
            ], 'integer'],
            [['type_mashine', 'serial_number', 'model', 'brand'], 'string', 'max' => 255],
            ['date_install', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_build', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_purchase', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_connection_monitoring', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['imei_id'], 'exist', 'skipOnError' => true, 'targetClass' => Imei::className(), 'targetAttribute' => ['imei_id' => 'id']],
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
            'serial_number' => Yii::t('frontend', 'Serial number'),
            'company_id' => Yii::t('frontend', 'Company'),
            'address_id' => Yii::t('frontend', 'Address'),
            'type_mashine' => Yii::t('frontend', 'Type Mashine'),
            'number_device' => Yii::t('frontend', 'Number Device'),
            'level_signal' => Yii::t('frontend', 'Level Signal'),
            'bill_cash' => Yii::t('frontend', 'Bill Cash'),
            'door_position' => Yii::t('frontend', 'Door Position'),
            'door_block_led' => Yii::t('frontend', 'Door Block Led'),
            'status' => Yii::t('frontend', 'Status'),
            'current_status' => Yii::t('frontend', 'Current Status'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
            'brand' => Yii::t('frontend', 'Brand'),
            'model' => Yii::t('frontend', 'Model'),
            'date_install' => Yii::t('frontend', 'Date Install'),
            'date_build' => Yii::t('frontend', 'Date build'),
            'date_purchase' => Yii::t('frontend', 'Date Purchase'),
            'date_connection_monitoring' => Yii::t('frontend', 'Date connection to monitoring')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    /**
     * Returns imei statuses list
     *
     * @param mixed $status
     * @return array|mixed
     */
    public static function statuses($status = null)
    {
        $statuses = [
            self::STATUS_OFF => Yii::t('frontend', 'Disabled'),
            self::STATUS_ACTIVE => Yii::t('frontend', 'Active'),
            self::STATUS_UNDER_REPAIR => Yii::t('frontend', 'Under repair'),
            self::STATUS_JUNK => Yii::t('frontend', 'Junk'),
        ];

        if ($status === null) {
            return $statuses;
        }

        return $statuses[$status];
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()
            ->where(['status' => WmMashine::STATUS_ACTIVE])
            ->andWhere(['is_deleted' => false]);
//        return new UserQuery(get_called_class());
//        return parent::find()->where(['is_deleted' => 'false'])
//            ->andWhere(['status' => Imei::STATUS_ACTIVE]);
//            ->andWhere(['<', '{{%user}}.created_at', time()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getStatusOff()
    {
        return WmMashine::find()->where(['status' => WmMashine::STATUS_OFF])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(AddressBalanceHolder::className(), ['id' => 'address_id']);
    }
}
