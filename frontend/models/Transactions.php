<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $card_no
 * @property string $imei
 * @property int $operation
 * @property string $amount
 * @property string $comment
 * @property string raw_data
 * @property string $operation_time
 * @property int $created_at
 */
class Transactions extends \yii\db\ActiveRecord
{
    const OPERATION_PAYMENT = 0;
    const OPERATION_INCOME = 1;
    const OPERATION_FAIL = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['card_no', 'operation', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['comment', 'raw_data'], 'string'],
            [['operation_time'], 'safe'],
            [['imei'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_no' => 'Card no',
            'imei' => 'Imei',
            'operation' => 'Operation',
            'amount' => 'Amount',
            'comment' => 'Comment',
            'raw_data' => 'Raw data',
            'operation_time' => 'Operation Time',
            'created_at' => 'Created At',
        ];
    }

    public static function statuses($status = null)
    {
        $statuses = [
            self::OPERATION_PAYMENT => Yii::t('payment', 'Operation payment'),
            self::OPERATION_INCOME => Yii::t('payment', 'Operation income'),
            self::OPERATION_FAIL => Yii::t('payment', 'Operation fail')
        ];

        if ($status === null) {
            return $statuses;
        }

        return $statuses[$status];
    }
}
