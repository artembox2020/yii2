<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address_balance_holder".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $balance_holder_id
 * @property int $created_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property BalanceHolder $balanceHolder
 * @property Floor[] $floors
 */
class AddressBalanceHolder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_balance_holder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance_holder_id'], 'required'],
            [['balance_holder_id', 'created_at', 'deleted_at'], 'integer'],
            [['name', 'address'], 'string', 'max' => 255],
            [['is_deleted'], 'string', 'max' => 1],
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
            'name' => Yii::t('frontend', 'Name'),
            'address' => Yii::t('frontend', 'Address'),
            'balance_holder_id' => Yii::t('frontend', 'Balance Holder'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
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
    public function getFloors()
    {
        return $this->hasMany(Floor::className(), ['address_balance_holder_id' => 'id']);
    }
}
