<?php

namespace frontend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class WmLog
 * @package frontend\models
 * @property int $id
 * @property int $company_id
 * @property int $address_id
 * @property int $imei_id
 * @property integer $date
 * @property integer $imei
 * @property integer $unix_time_offset
 * @property integer $number
 * @property integer $signal
 * @property integer $status
 * @property float $price
 * @property float $account_money
 * @property integer $washing_mode
 * @property integer $wash_temperature
 * @property integer $spin_type
 * @property double $prewash
 * @property double $rinsing
 * @property double $intensive_wash
 */
class WmLog extends ActiveRecord
{
    /** @var array $current_state */
    public $current_state = [
        '-19' => 'error_ue',
        '-18' => 'error_te',
        '-17' => 'error_oe_of',
        '-16' => 'error_le',
        '-15' => 'error_he',
        '-14' => 'error_fe',
        '-13' => 'error_de',
        '-12' => 'error_ce',
        '-11' => 'error_be',
        '-10' => 'error_ae',
        '-9' => 'error_9e_uc',
        '-8' => 'error_8e',
        '-7' => 'error_5e',
        '-6' => 'error_4e',
        '-5' => 'error_3e',
        '-4' => 'error_1e',
        '-3' => 'zero_work',
        '-2' => 'freeze_with_water',
        '-1' => 'no_connect_mcd',
        'no_power',
        'power_on_washer',
        'refill_washer',
        'washing_dress',
        'rising_dress',
        'extraction_dress',
        'washing_end',
        'washer_free',
        'nulling_washer',
        'connect_mcd',
        'sub_by_work',
        'max_washer_event'
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
