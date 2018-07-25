<?php

namespace frontend\models;

use phpDocumentor\Reflection\Types\Integer;
use Yii;
use frontend\models\ImeiData;
use frontend\models\GdMashine;
use frontend\models\WmMashine;
use frontend\services\globals\Entity;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "imei".
 *
 * @property int $id
 * @property int $imei
 * @property int $address_id
 * @property string $type_packet
 * @property int $imei_central_board
 * @property string $firmware_version
 * @property string $type_bill_acceptance
 * @property string $serial_number_kp
 * @property string $phone_module_number
 * @property string $crash_event_sms
 * @property int $critical_amount
 * @property int $time_out
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 * @property int $company_id
 * @property int $balance_holder_id
 * @property int $status
 *
 * @property AddressBalanceHolder $address
 * @property Machine[] $machines
 */
class Imei extends \yii\db\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_UNDER_REPAIR = 2;
    const STATUS_JUNK = 3;

    public $current_status = [
        'Off',
        'On',
        'Under repair',
        'Junk'
    ];

    /** @var $model */
    private $model;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imei';
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
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei', 'address_id', 'imei_central_board', 'critical_amount', 'time_out', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['imei', 'address_id', 'company_id', 'balance_holder_id', 'status'], 'required'],
            [['type_packet', 'firmware_version', 'type_bill_acceptance', 'serial_number_kp', 'phone_module_number', 'crash_event_sms'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            ['imei', 'unique',
                'targetClass' => Imei::className(),
                'filter' => function ($query) {
                    if (!$this->getModel()->isNewRecord) {
                        $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                    }
                }
            ],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => AddressBalanceHolder::className(), 'targetAttribute' => ['address_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'imei' => Yii::t('frontend', 'Imei'),
            'init' => Yii::t('frontend', 'Init'),
            'address' => Yii::t('frontend', 'Address'),
            'address_id' => Yii::t('frontend', 'Address'),
            'balanceHolder' => Yii::t('frontend', 'Balance Holder'),
            'balance_holder_id' => Yii::t('frontend', 'Balance Holder Id'),
            'type_packet' => Yii::t('frontend', 'Type Packet'),
            'imei_central_board' => Yii::t('frontend', 'Imei Central Board'),
            'firmware_version' => Yii::t('frontend', 'Firmware Version'),
            'type_bill_acceptance' => Yii::t('frontend', 'Type Bill Acceptance'),
            'serial_number_kp' => Yii::t('frontend', 'Serial Number Kp'),
            'phone_module_number' => Yii::t('frontend', 'Phone Module Number'),
            'crash_event_sms' => Yii::t('frontend', 'Crash Event Sms'),
            'critical_amount' => Yii::t('frontend', 'Critical Amount'),
            'time_out' => Yii::t('frontend', 'Time Out'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Update At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'addressName' => Yii::t('frontend', 'Address'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
        ];
    }

//    /**
//     * @inheritdoc
//     */
//    public function setModel($model)
//    {
//        $this->imei = $model->imei;
//        $this->model = $model;
//
//        return $this->model;
//    }

    /**
     * @inheritdoc
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = new Imei();
        }

        return $this->model;
    }
    
    /**
     * @return null|\yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(AddressBalanceHolder::className(), ['id' => 'address_id'])
                    ->andWhere(['address_balance_holder.status' => AddressBalanceHolder::STATUS_BUSY]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceHolder()
    {
        return $this->hasOne(BalanceHolder::className(), ['id' => 'balance_holder_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     *  get Initialisation status
     * @return string
     */
    public function getInit()
    {
       if (!empty($this->firmware_version)) {
           return 'Ok';
       }

       return Yii::t('frontend', 'Not initialized');
    }

    /**
     * @return string
     */
    public function getAddressName()
    {
        $address = $this->address;

        return $address ? $address->name : '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImeiData()
    {
        return $this->hasMany(ImeiData::className(), ['imei_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmMashine()
    {
        return $this->hasMany(WmMashine::className(), ['imei_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMachineStatus()
    {
        return $this->hasMany(WmMashine::className(), ['imei_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGdMashine()
    {
        return $this->hasMany(GdMashine::className(), ['imei_id' => 'id']);
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
        return parent::find()->where(['imei.is_deleted' => false]);
                             //->andWhere(['imei.status' => Imei::STATUS_ACTIVE]);
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
        return Imei::find()->where(['status' => Imei::STATUS_OFF])->all();
    }

    /**
     * @param Imei $imei
     * @return int
     */
    public static function getStatus(Imei $imei)
    {
        return $imei->status;
    }

    /**
     * @param $int
     * @return array|mixed
     */
    public static function checkStatus($int)
    {
        return self::statuses($int);
    }
    
    /**
     * Binds imei to address
     * 
     * @param integer $addressId
     * @param bool $checkStatus
     * @return void
     */
    public function bindToAddress($addressId, $checkStatus = false) {
        if ($checkStatus) {
            if ($this->status == Imei::STATUS_ACTIVE) {
                $this->address_id = $addressId;
                $this->save();
            }
        }
        else {
            $this->status = Imei::STATUS_ACTIVE;
            $this->address_id = $addressId;
            $this->save();
        }
    }
    
    /**
     * Gets the number of imeis, binded to address
     *
     * @param integer $addressId
     * @return integer
     */
    public function getCountImeiBindedToAddress($addressId) {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new Imei())
                        ->andWhere(['address_id' => $addressId, 'status' => Imei::STATUS_ACTIVE]);
        
        return $query->count();                
    }

    /**
     * @return void
     */
    public function afterSave($insert, $attr) {
        parent::afterSave($insert, $attr);

        $entity = new Entity();
        $address = $entity->getUnitPertainCompany($this->address_id, new AddressBalanceHolder());

        // change statuses
        if ($this->status == Imei::STATUS_ACTIVE) {
            $address->status = AddressBalanceHolder::STATUS_BUSY;
            $address->save();
            
            $imeisQuery = $entity->getUnitsQueryPertainCompany(new Imei());
            $imeis = $imeisQuery
                         ->andWhere(['address_id' => $this->address_id])
                         ->andWhere(['!=', 'id', $this->id])
                         ->all();

            foreach($imeis as $imei) {
                $imei->status = Imei::STATUS_OFF;
                $imei->save();
            }
        }
        else {
            if (!$this->getCountImeiBindedToAddress($this->address_id)) {
                $address->status = AddressBalanceHolder::STATUS_FREE;
                $address->save();
            }
        }

        // check and change status for the previous address
        if (
            !empty($attr['address_id']) && 
            !$this->getCountImeiBindedToAddress($attr['address_id'])
           )
        {
            $prevAddress = $entity->getUnitPertainCompany(
                $attr['address_id'], new AddressBalanceHolder(), false
            );

            if ($prevAddress) {
                $prevAddress->status = AddressBalanceHolder::STATUS_FREE;
                $prevAddress->save();
            }
        }
    }
    
    /** 
     * @return true|false
     */
    public function beforeDelete() {
        if (!parent::beforeDelete()) {
            
            return false;
        }
    
        $entity = new Entity();
        $address = $entity->getUnitPertainCompany(
            $this->address_id, new AddressBalanceHolder(), false
        );
        if ($address) {
            $address->status = AddressBalanceHolder::STATUS_FREE;
            $address->save();
        }
        
        return true;
    }
}
