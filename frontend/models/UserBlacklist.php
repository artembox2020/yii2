<?php

namespace frontend\models;

use DateTime;
use frontend\services\custom\Debugger;
use nepster\basis\helpers\DateTimeHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class UserBlacklist
 * @package frontend\models
 * @property int $id
 * @property int $company_id
 * @property int $user_id
 * @property int $author_id
 * @property string $comment
 * @property int $unix_time_offset
 */
class UserBlacklist extends \yii\db\ActiveRecord
{
    const ZERO = '0';

    const REASON_SUSPICIOUS_ACTIVITY = 'Suspicious activity';
    const REASON_TOO_MUCH_TRAFFIC = 'Too much traffic';

    const BLOCK_REASONS = [
        self::REASON_SUSPICIOUS_ACTIVITY => self::REASON_SUSPICIOUS_ACTIVITY,
        self::REASON_TOO_MUCH_TRAFFIC => self::REASON_TOO_MUCH_TRAFFIC
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {

        return 'user_blacklist';
    }

    public function rules() {

        return [
            /* your other rules */
            [['user_id', 'company_id', 'author_id', 'unix_time_offset'], 'integer'],
            [['comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'user_id' => Yii::t('frontend', 'User Id'),
            'company_id' => Yii::t('frontend', 'Company Id'),
            'author_id' => Yii::t('frontend', 'Author Id'),
            'unix_time_offset' => Yii::t('frontend', 'Timestamp'),
            'comment' => Yii::t('frontend', 'Comment')
        ];
    }

    /**
     * Reverses user blacklistng (block<->unblock)
     * 
     * @param int $userId
     * @param int $authorId
     * @param int $companyId
     * @param string $comment
     */
    public function reverseUser($userId, $authorId, $companyId, $comment)
    {
        if ($item=$this->getUserItem($userId, $companyId)) {
            $item->delete();
            $this->unblockAllUserCompanyCards($userId, $companyId);
        } else {
            $this->blockUser($userId, $authorId, $companyId, $comment, false);
            $this->blockAllUserCompanyCards($userId, $companyId);
        }
    }

    /**
     * Blacklists the user
     * 
     * @param int $userId
     * @param int $authorId
     * @param int $companyId
     * @param string $comment
     * @param bool $checkUser
     */
    public function blockUser($userId, $authorId, $companyId, $comment, $checkUser = true)
    {
        if (!$checkUser || !$this->getUserItem($userId, $companyId)) {
            $item = new UserBlacklist();
            $item->user_id = $userId;
            $item->author_id = $authorId;
            $item->company_id = $companyId;
            $item->comment = $comment;
            $item->unix_time_offset = time();
            $item->save();
        }
    }

    /**
     * Gets user item from blacklisting
     * 
     * @param int $userId
     * @param int $companyId
     *
     * @return \frontend\models\UserBlacklist | bool
     */
    public function getUserItem($userId, $companyId)
    {
        $item = self::find()->andWhere(['user_id' => $userId, 'company_id' => $companyId])->one();

        if ($item) {

            return $item;;
        }

        return false;
    }

    /**
     * Blocks all user company cards
     * 
     * @param int $userId
     * @param int $companyId
     */
    public function blockAllUserCompanyCards($userId, $companyId)
    {
        $conditions = ['user_id' => $userId, 'company_id' => $companyId, 'status' => CustomerCards::STATUS_ACTIVE];
        CustomerCards::updateAll(['status' => CustomerCards::STATUS_INACTIVE], $conditions);
    }

    /**
     * Unblocks all user company cards
     * 
     * @param int $userId
     * @param int $companyId
     */
    public function unblockAllUserCompanyCards($userId, $companyId)
    {
        $conditions = ['user_id' => $userId, 'company_id' => $companyId, 'status' => CustomerCards::STATUS_INACTIVE];
        CustomerCards::updateAll(['status' => CustomerCards::STATUS_ACTIVE], $conditions);
    }

    /**
     * Gets all block reasons as array
     * 
     * @return array
     */
    public static function getBlockReasons()
    {
        $blockReasons = [];

        foreach (self::BLOCK_REASONS as $key => $reason) {
            $blockReasons[$key] = Yii::t('map', $reason);
        }

        return $blockReasons;
    }
}
