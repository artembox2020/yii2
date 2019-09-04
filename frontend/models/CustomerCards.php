<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "customer_cards".
 *
 * @property int $id
 * @property int $card_no
 * @property float $balance
 * @property int $discount
 * @property int $status
 * @property int user_id
 * @property int company_id
 * @property int $created_at
 * @property int $deleted_at
 * @property int $is_deleted
 */
class CustomerCards extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

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

    /**
     * Get card statuses
     *
     * @param null $status
     * @return array|mixed
     */
    public static function statuses($status = null)
    {
        $statuses = [
            self::STATUS_INACTIVE => Yii::t('common', 'Card inactive'),
            self::STATUS_ACTIVE => Yii::t('common', 'Card active'),
        ];

        if ($status === null) {
            return $statuses;
        }

        return $statuses[$status];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getCompany()
    {

        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * Reverses card status
     * 
     * @return bool
     */
    public function updateStatus()
    {
        $userBlacklist = new UserBlacklist();

        // if card user in blacklist then do nothing
        if ($userBlacklist->getUserItem($this->user->id, $this->company->id)) {

            return false;
        }

        $this->status = ($this->status == self::STATUS_INACTIVE) ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
        $this->update();

        return true;
    }
}
