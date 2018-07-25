<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use frontend\services\globals\Entity;

/**
 * This is the model class for table "address_balance_holder".
 *
 * @property int $id
 * @property int $company_id
 * @property int $balance_holder_id
 * @property string $name
 * @property string $address
 * @property int $floor
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 * @property int $number_of_floors
 * @property int $date_inserted
 * @property int $date_connection_monitoring
 *
 * @property BalanceHolder $balanceHolder
 * @property Imei[] $imeis
 */
class AddressBalanceHolder extends \yii\db\ActiveRecord
{
    const STATUS_FREE = 0;
    const STATUS_BUSY = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_balance_holder';
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
            ['date_inserted', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_connection_monitoring', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            [['company_id', 'balance_holder_id', 'number_of_floors', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['balance_holder_id', 'address'], 'required'],
            [['name', 'address', 'floor'], 'string', 'max' => 255],
            [['balance_holder_id'], 'exist', 'skipOnError' => true, 'targetClass' => BalanceHolder::className(), 'targetAttribute' => ['balance_holder_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'company_id' => Yii::t('frontend', 'Company'),
            'name' => Yii::t('frontend', 'Name'),
            'address' => Yii::t('frontend', 'Address'),
            'floor' => Yii::t('frontend', 'Floor'),
            'number_of_floors' => Yii::t('frontend', 'Number of Floors'),
            'balance_holder_id' => Yii::t('frontend', 'Balance Holder'),
            'date_inserted' => Yii::t('frontend', 'Date Inserted'),
            'date_connection_monitoring' => Yii::t('frontend', 'Date connection monitoring'),
            'countWashMachine' => Yii::t('frontend', 'Count Wash Machine'),
            'countGelDispenser' => Yii::t('frontend', 'Count Gd Machine'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
            'imeis' => Yii::t('frontend', 'Imei'),
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getImeis()
    {
        return $this->hasMany(Imei::className(), ['address_id' => 'id'])
                    ->andWhere(['status' => Imei::STATUS_ACTIVE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['address_id' => 'id'])
                    ->andWhere(['status' => Imei::STATUS_ACTIVE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWashMachines()
    {
        return $this->hasMany(WmMashine::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGelDispenser()
    {
        return $this->hasMany(GdMashine::className(), ['address_id' => 'id']);
    }
    
    /**
     * WashMashines to address Count
     * @return int|string
     */
    public function getCountWashMachine() {
        
        return $this->getWashMachines()->count();
    }

    /**
     * GdMashines to address Count
     * @return int|string
     */
    public function getCountGelDispenser() {
        
        return $this->getGelDispenser()->count();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['address_balance_holder.is_deleted' => false]);
    }

    /**
     * @return true|false
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            
            return false;
        }

        $entity = new Entity();
        $imeis = $entity->getUnitsQueryPertainCompany(new Imei())
                        ->andWhere(['address_id' => $this->id, 'status' => Imei::STATUS_ACTIVE])
                        ->all();
        foreach($imeis as $imei) {        
            $imei->status = Imei::STATUS_OFF;
            $imei->save();
        }

        return true;
    }
}
