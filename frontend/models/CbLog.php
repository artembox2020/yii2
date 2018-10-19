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
