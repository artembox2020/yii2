<?php

namespace common\models;

use Yii;
use common\services\YouTubeChannel;
use common\components\SmsGate\SmsGate;

/**
 * This is the model class for table "yt_channel_notification".
 *
 * @property int $id
 * @property string $channel_id
 * @property string $phone
 * @property string $created_at
 * @property string $deleted_at
 *
 * @property YtChannel $channel
 */
class YtChannelNotification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yt_channel_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'phone'], 'required', 'on' => 'default'],
            [['created_at', 'deleted_at'], 'safe'],
            [['channel_id'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
            [['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => YtChannel::className(), 'targetAttribute' => ['channel_id' => 'id'], 'on' => 'default'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => 'Channel ID',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['channel_id', 'phone', 'created_at', 'deleted_at'],
            'SEARCH'  => ['channel_id', 'phone', 'created_at', 'deleted_at'],
        ];
    }

    /**
     * Gets query for [[Channel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(YtChannel::className(), ['id' => 'channel_id']);
    }

    public function notify()
    {
        $channelData = Yii::$app->cache->getOrSet("channel_" . $this->channel_id . '_table_data', function () {

            return (new YouTubeChannel())->getChannelTableData($this->channel_id);
        }, 300);

        if (empty($channelData)) {

            return null;
        }

        $channel = Yii::$app->cache->getOrSet("channel_" . $this->channel_id . '_db_table_data', function () {

            return $this->getChannel()->one();
        }, 300);

        if ($channelData['post_count']  > $channel->post_count) {
            $channel->attributes = $channelData;
            $channel->save();
            $smsGate = new SmsGate();

            return $smsGate->send($this->phone, "Channel " . $channel->title . ' published new video');
        }
    }
}
