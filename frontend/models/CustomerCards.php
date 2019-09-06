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

        // if no user and  card user in blacklist then do nothing
        if (!empty($this->user) && $userBlacklist->getUserItem($this->user->id, $this->company->id)) {

            return false;
        }

        $this->status = ($this->status == self::STATUS_INACTIVE) ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
        $this->update();

        return true;
    }

    /**
     * Checks card is available to be assigned to a user
     * 
     * @param int $userId
     * @param int $cardNo
     * 
     * @return bool
     */
    public function checkCardAvailable($userId, $cardNo)
    {
        $card = self::find()->andWhere(['status' => self::STATUS_ACTIVE])
                            ->andWhere(['or', 
                                ['user_id' => 0], ['is', 'user_id', null]
                            ])
                            ->andWhere(['or', 
                                ['is_deleted' => 0], ['is', 'is_deleted', null]
                            ])
                            ->andWhere(['card_no' => $cardNo])
                            ->limit(1)
                            ->one();

        $userBlacklist = new UserBlacklist();

        if (!$card || (!empty($card->company_id) && $userBlacklist->getUserItem($userId, $card->company_id))) {

            return false;
        }

        return true;
    }

    /**
     * Assigns card to a user
     * 
     * @param int $userId
     * @param int $cardNo
     */
    public function assignCard($userId, $cardNo)
    {
        $card = self::find()->andWhere(['card_no' => $cardNo])->one();
        $card->user_id = $userId;
        $card->save();
    }
}
