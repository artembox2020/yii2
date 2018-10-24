<?php

namespace frontend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class CbLog
 * @package frontend\models
 * @property int $id
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
class CbLog extends ActiveRecord
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
}
