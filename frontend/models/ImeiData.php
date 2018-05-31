<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "imei_data".
 *
 * @property int $id
 * @property int $imei_id
 * @property int $created_at
 * @property int $imei
 * @property int $level_signal
 * @property int $on_modem_account
 * @property int $in_banknotes
 * @property int $money_in_banknotes
 * @property int $fireproof_residue
 * @property int $price_regim
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
// * @property Imei $imei
 * @property WmMashine[] $wmMashines
 */
class ImeiData extends \yii\db\ActiveRecord
{
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
        return 'imei_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei_id', 'company_id', 'status'], 'required'],
            [['imei_id', 'created_at', 'imei', 'level_signal', 'on_modem_account', 'in_banknotes', 'money_in_banknotes', 'fireproof_residue', 'price_regim', 'updated_at', 'deleted_at'], 'integer'],
            [['is_deleted'], 'string', 'max' => 1],
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
            'company_id' => Yii::t('frontnd', 'Company'),
            'status' => Yii::t('frontend', 'Status'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'imei' => Yii::t('frontend', 'Imei'),
            'level_signal' => Yii::t('frontend', 'Level Signal'),
            'on_modem_account' => Yii::t('frontend', 'On Modem Account'),
            'in_banknotes' => Yii::t('frontend', 'In Banknotes'),
            'money_in_banknotes' => Yii::t('frontend', 'Money In Banknotes'),
            'fireproof_residue' => Yii::t('frontend', 'Fireproof Residue'),
            'price_regim' => Yii::t('frontend', 'Price Regim'),
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
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmMashines()
    {
        return $this->hasMany(WmMashine::className(), ['imei_id' => 'id']);
    }
}
