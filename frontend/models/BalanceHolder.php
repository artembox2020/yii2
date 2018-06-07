<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "balance_holder".
 *
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string $address
 * @property string $phone
 * @property string $contact_person
 * @property int $company_id
 * @property int $created_at
 * @property int $is_deleted
 * @property int $deleted_at
 * @property string $position
 * @property int $date_start_cooperation
 * @property int $date_connection_monitoring
 *
 * @property AddressBalanceHolder[] $addressBalanceHolders
 * @property Company $company
 */
class BalanceHolder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balance_holder';
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
            [['company_id', 'created_at', 'deleted_at'], 'integer'],
            [['name', 'city', 'address', 'contact_person', 'position'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 100],
            [['is_deleted'], 'string', 'max' => 1],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'name' => Yii::t('frontend', 'Balance Holder Name'),
            'city' => Yii::t('frontend', 'City'),
            'Count Addresses' => Yii::t('frontend', 'Count Addresses'),
            'address' => Yii::t('frontend', 'Address amount'),
            'phone' => Yii::t('frontend', 'Phone'),
            'contact_person' => Yii::t('frontend', 'Contact Person'),
            'company_id' => Yii::t('frontend', 'Company ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
            'position' => Yii::t('frontend', 'Position'),
            'date_start_cooperation' => Yii::t('frontend', 'Date on start to cooperation'),
            'date_connection_monitoring' => Yii::t('frontend', 'Date connection to monitoring'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressBalanceHolders()
    {
        return $this->hasMany(AddressBalanceHolder::className(), ['balance_holder_id' => 'id']);
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
    public function getOtherContactPerson()
    {
        return $this->hasMany(OtherContactPerson::className(), ['balance_holder_id' => 'id']);
    }

    /**
     * @return int|string
     */
    public function getCountAddresses()
    {
        return $this->getAddressBalanceHolders()->count();
    }

    public function getCountWashpay()
    {
        foreach ($this->getAddressBalanceHolders() as $addresses) {
            $addresses[]
        }
    }
}
