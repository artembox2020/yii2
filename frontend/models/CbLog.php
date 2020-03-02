<?php

namespace frontend\models;

use DateTime;
use frontend\services\custom\Debugger;
use nepster\basis\helpers\DateTimeHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class CbLog
 * @package frontend\models
 * @property int $id
 * @property int $company_id
 * @property int $address_id
 * @property int $imei_id
 * @property integer $date
 * @property integer $imei
 * @property string $device
 * @property integer $signal
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

    const ZERO = '0';
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
        'put_money',
        'err_update_fram',
        'last_poweroff',
        'cashless_reserved',
        'cashless_vend_denied',
        'cashless_cmd_out_of_seq',
        'cashless_refund_ok',
        'cashless_refund_error',
        'cashless_man_defined_error',
        'cashless_communications_error',
        'cashless_reader_error',
        'cashless_payment_media_error',
        'cashless_unk_error',
        'eth_conn_ok',
        'err_eth_cable',
        'err_eth_ip_addr',
        'err_eth_server_conn',
        'err_eth_data_send',
        'serer_time_set',
        'lock_validator',
        'reboot_unlock_validator',
        'lock_wm',
        'unlock_wm',
        'remote_pay',
        'http_201_response',
        'http_206_response',
        'http_400_response',
        'http_404_response',
        'http_500_response',
        'http_601_response',
        'http_unknown_response',
        'coordinator_reboot',
        'service_entry',
        'cmd_reserved0',
        'cmd_reset_cpu',
        'cmd_reset_vend',
        'cmd_reset_coord',
        'cmd_reset_modem',
        'cmd_format_disk',
        'cmd_time_set',
        'cmd_validator_off',
        'cmd_reserved1',
        'cmd_reserved2',
        'cmd_reserved3'
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
            'rate' => Yii::t('logs', 'Rate')
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

    /**
     * @param $address_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getAddress($address_id)
    {
        return AddressBalanceHolder::find(['id' => $address_id])->one();
    }

    /**
     * @param $date
     * @param $address_id
     * @return false|string|null
     * @throws \yii\db\Exception
     */
    public function getSumDaysPreviousAnAddress($date, $address_id)
    {
        $diff = Yii::$app->db->createCommand(
            'SELECT `date` FROM `cb_log`
                    WHERE `date` < :date
                    and `address_id` = :address_id
                    ORDER BY `date` ASC
                    LIMIT 1')
            ->bindValue(':date', $date)
            ->bindValue(':address_id', $address_id)
            ->queryScalar();

//        Debugger::dd($diff);

        $a = DateTimeHelper::diffDaysPeriod($date, $diff, $showTimeUntilDay = true);

//        echo $a;die;
        if ($diff) {
            $date = date('Y-m-d', $date);
            $diff = date('Y-m-d', $diff);
//            echo $date;die;
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($diff);
            $interval = $datetime1->diff($datetime2);
//            return $interval->format('%R%a');
            return $a;
        }


        return $diff;
    }

    public function getWmLog()
    {
        return $this->hasOne(WmLog::className(), ['id' => $this->imei_id])->one();
    }
}
