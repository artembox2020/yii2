<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use backend\models\search\CardSearch;

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

    /**
     * Finds last transaction by card number
     * 
     * @param int $cardNo
     * @param string $select
     * 
     * @return frontend\models\Transactions|null
     */
    public static function findLastTransactionByCardNo($cardNo, $select = "*")
    {

        return self::find()->select($select)->andWhere(['card_no' => $cardNo])
                           ->orderBy(['created_at' => SORT_DESC])->limit(1)->one();
    }

    /**
     * Finds circulation by card number and initial timestamp
     * 
     * @param int $cardNo
     * @param int $timestampSince
     * 
     * @return int|null
     */
    public function findCirculationByCardNo($cardNo, $timestampSince = 0)
    {

        return self::find()->andWhere(['operation' => self::OPERATION_PAYMENT, 'card_no' => $cardNo])
                           ->andWhere(['>=', 'created_at', $timestampSince])
                           ->sum('amount');
    }

    /**
     * Finds circulation by user id and initial timestamp
     * 
     * @param int $userId
     * @param int $timestampSince
     * 
     * @return int|null
     */
    public function findCirculationByUserId($userId, $timestampSince = 0)
    {
        $cardSearch = new CardSearch();
        $cardNos = $cardSearch->findCardsByUserId($userId);

        return self::find()->andWhere(['operation' => self::OPERATION_PAYMENT, 'card_no' => $cardNos])
                           ->andWhere(['>=', 'created_at', $timestampSince])
                           ->sum('amount');
    }
}
