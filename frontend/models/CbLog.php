<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class CbLog
 * @package frontend\models
 * @property int $id
 * @property int $imei_id
 * @property integer $date
 * @property integer $imei
 * @property integer $unix_time_offset
 * @property integer $status
 * @property float $fireproof_counter_hrn
 * @property float $fireproof_counter_card
 * @property float $collection_counter
 * @property double $notes_billiards_pcs
 * @property double $rate
 * @property float $refill_amount
 * @property boolean $is_deleted
 */
class CbLog extends \yii\db\ActiveRecord
{
    /** @var array $current_state */
    public $current_state = [
        '-11' => 'invalid_command',
        '-10' => 'bill_reject',
        '-9' => 'stacker_problem',
        '-8' => 'bill_fish',
        '-7' => 'sensor_problem',
        '-6' => 'bill_remove',
        '-5' => 'bill_jam',
        '-4' => 'checksum_error',
        '-3' => 'motor_failure',
        '-2' => 'com_error',
        '-1' => 'cpu_problem',
        'link_pc',
        'update_software',
        'change_technical',
        'change_economical',
        'change_remoter',
        'request_from_server',
        'time_correction',
        'full_bill_acceptor',
        'collection',
        'technical_bill',
        'activation_card',
        'data_card',
        'cash_card',
        'start_board',
        'unlink_pc',
        'repair',
        'open_door',
        'put_money'
        ];

    /** @var $model */
    private $model;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cb_log';
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
     * @inheritdoc
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = new Imei();
        }

        return $this->model;
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['cb_log.is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    public function getAddress()
    {
        return $this->hasOne(AddressBalanceHolder::className(), ['id' => $this->getImei()]);
    }
}
