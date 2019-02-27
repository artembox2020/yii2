<?php

namespace frontend\models;

use DateTime;
use frontend\services\custom\Debugger;
use nepster\basis\helpers\DateTimeHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class CbEncashment
 * @property int $id
 * @property int $company_id
 * @property int $address_id
 * @property int $imei_id
 * @property integer $imei
 * @property string $device
 * @property integer $unix_time_offset
 * @property integer $status
 * @property float $fireproof_counter_hrn
 * @property float $collection_counter
 * @property double $notes_billiards_pcs
 * @property float $recount_amount
 * @property string $banknote_face_values
 * @property string $coin_face_values
 * @property double $amount_of_coins
 * @property boolean $is_deleted
 */
class CbEncashment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {

        return 'cb_encashment';
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
                'class' => TimestampBehavior::className()
            ]
        ];
    }

    public function rules() {

        return [
            /* your other rules */
            [['created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['recount_amount'], 'double']
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
        ];
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['cb_encashment.is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    /**
     * @param $address_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getAddress($address_id)
    {
        return AddressBalanceHolder::find(['id' => $address_id])->one();
    }

    /**
     * Updates `recount_amount` field of cb_encashment table
     * 
     * @param int $logId
     * @param int $recountAmount
     */
    public function updateRecountAmount($logId, $recountAmount)
    {
        $item = CbEncashment::findOne($logId);

        if (!empty($item)) {
            $item->recount_amount = $recountAmount;
            $item->save();
        }
    }

    /**
     * Check constraint: unique key <imei_id, unix_time_offset> 
     * 
     * @param int $imeiId
     * @param int $unixTimeOffset
     * @return bool
     */
    public function checkImeiIdUnixTimeOffsetUnique($imeiId, $unixTimeOffset)
    {
        $query = CbEncashment::find()->andWhere(['imei_id' => $imeiId, 'unix_time_offset' => $unixTimeOffset]);

        return $query->count() > 0 ? false : true;
    }
}
