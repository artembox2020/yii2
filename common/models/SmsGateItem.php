<?php

namespace common\models;

use Yii;
use common\components\SmsGate\Native\Turbosms;

/**
 * This is the model class for table "sms_gate_item".
 *
 * @property int $id
 * @property int $sms_gate_id
 * @property string $token
 * @property string $login
 * @property string $pass
 * @property float $balance
 * @property string $created_at
 * @property string|null $deleted_at
 *
 * @property SmsGate $smsGate
 */
class SmsGateItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms_gate_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sms_gate_id', 'token', 'login', 'pass', 'balance'], 'required'],
            [['sms_gate_id'], 'integer'],
            [['balance'], 'number'],
            [['created_at', 'deleted_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['login'], 'string', 'max' => 64],
            [['pass'], 'string', 'max' => 32],
            [['sms_gate_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmsGate::className(), 'targetAttribute' => ['sms_gate_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sms_gate_id' => 'Sms Gate ID',
            'token' => 'Token',
            'login' => 'Login',
            'pass' => 'Pass',
            'balance' => 'Balance',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[SmsGate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmsGate()
    {
        return $this->hasOne(SmsGate::className(), ['id' => 'sms_gate_id']);
    }

    public function getSmsGateInstance()
    {
        $gate = $this->getSmsGate()->one();

        switch ($gate->name) {
            case "turbosms":

                return new Turbosms($this->token, $this->login, $this->pass);
                break;
            default:
                return null;
        }
    }

    public function send($to, $text)
    {
        $gate = $this->getSmsGateInstance();

        $gate->send([$to], $text);
        $this->balance = $gate->getBalance();
        $this->save(false);
    }
}
