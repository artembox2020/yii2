<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "gd_mashine".
 *
 * @property int $id
 * @property int $imei_id
 * @property int $company_id
 * @property int $balance_holder_id
 * @property int $address_id
 * @property string $type_mashine
 * @property string $serial_number
 * @property int $gel_in_tank
 * @property int $bill_cash
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property Imei $imei
 */
class GdMashine extends \yii\db\ActiveRecord
{

    public $current_status = [
        'dz no connect',
        'dz connect',
        'dz ready',
        'dz vidacha',
        'dz no cashe',
        'dz change',
        'dz no flow',
        'dz error',
        'dz error param'
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
        return 'gd_mashine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei_id', 'status', 'company_id', 'balance_holder_id', 'address_id'], 'required'],
            [['imei_id', 'bill_cash', 'gel_in_tank', 'status'], 'integer'],
            [['type_mashine'], 'string'],
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
            'company_id' => Yii::t('frontend', 'Company'),
            'address_id' => Yii::t('frontend', 'Address'),
            'type_mashine' => Yii::t('frontend', 'Type Mashine'),
            'bill_cash' => Yii::t('frontend', 'Bill Cash'),
            'serial_number' => Yii::t('frontend', 'Serial Number'),
            'gel_in_tank' => Yii::t('frontend', 'Gel In Tank'),
            'status' => Yii::t('frontend', 'Status'),
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
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }
}
