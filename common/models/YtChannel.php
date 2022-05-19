<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yt_channel".
 *
 * @property string $id
 * @property string $title
 * @property string $published_at
 * @property string|null $descr
 * @property string $last_post
 * @property int $post_count
 * @property YtChannelNotification[] $ytChannelNotifications
 */
class YtChannel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yt_channel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'last_post', 'post_count'], 'required'],
            [['published_at'], 'safe'],
            [['descr'], 'string'],
            [['id', 'title', 'last_post'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'published_at' => 'Published At',
            'descr' => 'Descr',
        ];
    }

    /**
     * Gets query for [[YtChannelNotifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getYtChannelNotifications()
    {
        return $this->hasMany(YtChannelNotification::className(), ['channel_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ( ! empty($this->published_at)) {
            $this->published_at = date("Y-m-d H:i:s", strtotime($this->published_at));
        }

        return parent::beforeSave($insert);
    }
}
