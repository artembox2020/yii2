<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms_gate".
 *
 * @property int $id
 * @property string $name
 * @property float $sms_price
 *
 * @property SmsGateItem[] $smsGateItems
 */
class SmsGate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms_gate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'sms_price'], 'required'],
            [['sms_price'], 'number'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sms_price' => 'Sms Price',
        ];
    }

    /**
     * Gets query for [[SmsGateItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmsGateItems()
    {
        return $this->hasMany(SmsGateItem::className(), ['sms_gate_id' => 'id']);
    }
}
