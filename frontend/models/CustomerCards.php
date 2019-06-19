<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_cards".
 *
 * @property int $id
 * @property int $card_no
 * @property string $balance
 * @property int $discount
 * @property int $status
 * @property int $created_at
 * @property int $deleted_at
 * @property int $is_deleted
 */
class CustomerCards extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_cards';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['card_no', 'discount', 'status', 'created_at', 'deleted_at', 'is_deleted'], 'integer'],
            [['balance'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_no' => 'Card No',
            'balance' => 'Balance',
            'discount' => 'Discount',
            'status' => 'Status',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
