<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\web\view;

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
 * @property int $current_status
 *
 * @property Imei $imei
 */
class GdMashine extends \yii\db\ActiveRecord
{

    public $current_state = [
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

    public $level_signal;

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
            [['imei_id', 'bill_cash', 'gel_in_tank', 'status', 'current_status', 'level_signal'], 'integer'],
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
            'current_status' => Yii::t('frontend', 'Current Status'),
            'level_signal' => Yii::t('frontend', 'Level Signal')
        ];
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['gd_mashine.is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    /**
     * Gets current state of the machine
     * 
     * @return string|null
     */
    public function getState()
    {
        if (array_key_exists($this->current_status, $this->current_state)) {

            return $this->current_state[$this->current_status];
        }

        return null;
    }

    /**
     * Gets mashine query by imei id
     * 
     * @param int $imeiId
     * @return ActiveQuery
     */
    public static function getMachinesQueryByImeiId($imeiId)
    {
        $query = self::find()->andWhere(['imei_id' => $imeiId]);

        return $query;
    }

    /**
     * Gets state view
     * 
     * @return string
     */
    public function getStateView()
    {
        $viewObject = new View();

        return $viewObject->render('/wm-mashine/stateView', ['mashine' => $this]);
    }
}
