<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "other_contact_person".
 *
 * @property int $id
 * @property int $balance_holder_id
 * @property string $name
 * @property string $position
 * @property string $phone
 * @property int $created_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property BalanceHolder $balanceHolder
 */
class OtherContactPerson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other_contact_person';
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
            [['balance_holder_id'], 'required'],
            [['balance_holder_id', 'created_at', 'deleted_at'], 'integer'],
            [['name', 'position', 'phone'], 'string', 'max' => 255],
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
            'balance_holder_id' => Yii::t('frontend', 'Balance Holder ID'),
            'name' => Yii::t('frontend', 'Name'),
            'position' => Yii::t('frontend', 'Position'),
            'phone' => Yii::t('frontend', 'Phone'),
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
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }
}
