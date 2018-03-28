<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "imei".
 *
 * @property int $id
 * @property int $imei
 * @property int $address_id
 * @property string $type_packet
 * @property int $imei_central_board
 * @property string $firmware_version
 * @property string $type_bill_acceptance
 * @property string $serial_number_kp
 * @property string $phone_module_number
 * @property string $crash_event_sms
 * @property int $critical_amount
 * @property int $time_out
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property AddressBalanceHolder $address
 * @property Machine[] $machines
 */
class Imei extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imei';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei', 'address_id', 'imei_central_board', 'critical_amount', 'time_out', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['address_id'], 'required'],
            [['type_packet', 'firmware_version', 'type_bill_acceptance', 'serial_number_kp', 'phone_module_number', 'crash_event_sms'], 'string', 'max' => 255],
            [['is_deleted'], 'string', 'max' => 1],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => AddressBalanceHolder::className(), 'targetAttribute' => ['address_id' => 'id']],
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
            'address_id' => Yii::t('frontend', 'Address ID'),
            'type_packet' => Yii::t('frontend', 'Type Packet'),
            'imei_central_board' => Yii::t('frontend', 'Imei Central Board'),
            'firmware_version' => Yii::t('frontend', 'Firmware Version'),
            'type_bill_acceptance' => Yii::t('frontend', 'Type Bill Acceptance'),
            'serial_number_kp' => Yii::t('frontend', 'Serial Number Kp'),
            'phone_module_number' => Yii::t('frontend', 'Phone Module Number'),
            'crash_event_sms' => Yii::t('frontend', 'Crash Event Sms'),
            'critical_amount' => Yii::t('frontend', 'Critical Amount'),
            'time_out' => Yii::t('frontend', 'Time Out'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Update At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(AddressBalanceHolder::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMachines()
    {
        return $this->hasMany(Machine::className(), ['imei_id' => 'id']);
    }
}
