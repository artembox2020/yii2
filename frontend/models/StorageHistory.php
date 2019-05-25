<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class StorageHistory
 * @package frontend\models
 *
 * @property int $id
 * @property int $imei
 * @property int $company_id
 * @property int $address_id
 * @property int $imei_id
 * @property int $date_transfer_from_storage
 * @property int $number_device
 * @property string $type
 * @property int $ping
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property boolean $is_deleted
 * @property int $deleted_at
 */
class StorageHistory extends ActiveRecord
{
    const PHP_DATE_TIME_FORMAT = 'php:H:i d.m.Y';
    const WAREHOUSE = 'php:d.m.Y';
    const STATUS_OFF = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_UNDER_REPAIR = 2;
    const STATUS_JUNK = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage_history';
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
                    'is_deleted' => true
                ],
            ],
            [
                'class' => TimestampBehavior::className()
            ]
        ];
    }

    public function rules()
    {
        return parent::rules(); // TODO: Change the autogenerated stub
    }

    public function attributeLabels()
    {
        return parent::attributeLabels(); // TODO: Change the autogenerated stub
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()
            ->where(['storage_history.is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(AddressBalanceHolder::className(), ['id' => 'address_id']);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getLastPing()
    {
        $formattedDate = Yii::$app->formatter->asDate($this->ping, self::PHP_DATE_TIME_FORMAT);

        return $formattedDate;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getWarehouseTransferDate()
    {
        $formattedDate = Yii::$app->formatter->asDate($this->created_at, self::WAREHOUSE);

        return $formattedDate;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getStorageTransferDate()
    {
        $formattedDate = Yii::$app->formatter->asDate($this->date_transfer_from_storage, self::WAREHOUSE);

        return $formattedDate;
    }

    /**
     * @param null $status
     * @return array|mixed
     */
    public function getCurrentStatus($status = null)
    {
        $statuses = [
            self::STATUS_OFF => Yii::t('frontend', 'Disabled'),
            self::STATUS_ACTIVE => Yii::t('frontend', 'Active'),
            self::STATUS_UNDER_REPAIR => Yii::t('frontend', 'Under repair'),
            self::STATUS_JUNK => Yii::t('frontend', 'Junk'),
        ];

        if ($status === null) {
            return $statuses;
        }

        return $statuses[$status];
    }

    /**
     * @param $id
     * @return WmMashine|null
     */
    public function getInventoryNumber($id)
    {
        return WmMashine::findOne(['id' => $id]);
    }
}
