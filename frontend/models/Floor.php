<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "floor".
 *
 * @property int $id
 * @property int $floor_number
 * @property int $address_balance_holder_id
 * @property int $created_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property AddressBalanceHolder $addressBalanceHolder
 * @property Imei[] $imeis
 */
class Floor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['floor_number', 'address_balance_holder_id', 'created_at', 'deleted_at'], 'integer'],
            [['address_balance_holder_id'], 'required'],
            [['is_deleted'], 'string', 'max' => 1],
            [['address_balance_holder_id'], 'exist', 'skipOnError' => true, 'targetClass' => AddressBalanceHolder::className(), 'targetAttribute' => ['address_balance_holder_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'floor_number' => Yii::t('frontend', 'Floor Number'),
            'address_balance_holder_id' => Yii::t('frontend', 'Address Balance Holder ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressBalanceHolder()
    {
        return $this->hasOne(AddressBalanceHolder::className(), ['id' => 'address_balance_holder_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImeis()
    {
        return $this->hasMany(Imei::className(), ['floor_id' => 'id']);
    }
}
