<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\services\globals\Entity;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "address_imei_data".
 *
 * @property integer $address_id
 * @property integer $imei_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 */
class AddressImeiData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_imei_data';
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
                    'deleted_at' => time() + Jlog::TYPE_TIME_OFFSET
                ],
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => time() + Jlog::TYPE_TIME_OFFSET
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id', 'imei_id', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => Yii::t('frontend', 'Address ID'),
            'imei_id' => Yii::t('frontend', 'Imei ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
        ];
    }

    /**
     * writes item to the table and  returns its id
     * 
     * @param int $imeiId
     * @param int $addressId
     * @return int
     */
    public function createLog($imeiId, $addressId)
    {
        $imei = Imei::findOne($imeiId);

        if ($imeiId != 0 && (!$imei || $imei->status != Imei::STATUS_ACTIVE)) {

            return 0;
        }

        $query = AddressImeiData::find();
        $addressImeiItem = $query->andWhere(['imei_id' => $imeiId])
                                 ->orderBy(['created_at' => SORT_DESC])
                                 ->limit(1)
                                 ->one();

        if (!$addressImeiItem || $addressImeiItem->address_id != $addressId) {                               
            $this->isNewRecord = true;
            $this->address_id = $addressId;
            $this->imei_id = $imeiId;
            $this->is_deleted = false;
            $this->save();

            return $this->id;
        }
    }

    /**
     * gets imei id by address and timestamp
     * 
     * @param int $addressId
     * @param int $timestamp
     * @return int
     */
    public function getImeiIdByAddressAndTimestamp($addressId, $timestamp)
    {
        $query = AddressImeiData::find();
        $item = $query->andWhere(['address_id' => $addressId])
                      ->andWhere(['<=', 'created_at', $timestamp])
                      ->orderBy(['created_at' => SORT_DESC])
                      ->limit(1)
                      ->one();

        if (!$item) {

            return 0;
        }

        $query = AddressImeiData::find();
        $itemImei = $query->andWhere(['imei_id' => $item->imei_id])
                          ->andWhere(['<=', 'created_at', $timestamp])
                          ->orderBy(['created_at' => SORT_DESC])
                          ->limit(1)
                          ->one();

        if ($itemImei && $itemImei->address_id == $addressId) {

            return $item->imei_id;
        }

        return 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['address_imei_data.is_deleted' => false]);
    }
}
