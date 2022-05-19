<?php

namespace common\widgets\notify;

use Yii;
use yii\base\Widget;
use common\models\YtChannel;
use common\models\YtChannelNotification;
use common\services\YouTubeScraper;


class YoutubeNotifyWidget extends Widget
{
    const VIEW_TYPE_NEW = 'new_notification';
    const VIEW_TYPE_LIST = 'list_notification';

    public $viewType;

    public function init()
    {
        parent::init();
        if (empty($this->viewType)) {
            $this->viewType = self::VIEW_TYPE_NEW;
        }
    }

    public function run()
    {
        switch ($this->viewType) {
            case self::VIEW_TYPE_NEW:
                return $this->render('youtube_notify');
                break;
            case self::VIEW_TYPE_LIST:
                return $this->render('youtube_notify_list', []);
                break;
            default:
                return $this->render('youtube_notify');
        }
    }

    public static function notify($model)
    {
        if (!empty(Yii::$app->request->post('DynamicModel')) && array_key_exists('add_notify', Yii::$app->request->post('DynamicModel'))) {
            $model->load(Yii::$app->request->post('DynamicModel'));
            $channelId = Yii::$app->request->post('DynamicModel')['channel_id'];
            $phone = Yii::$app->request->post('DynamicModel')['phone'];

            $yt = new \common\services\YouTubeChannel();
            $tableData = $yt->getChannelTableData($channelId);

            if (!empty($tableData)) {
                $channel = YtChannel::findOne($channelId) ?? new YtChannel();

                if (empty($channel->id)) {
                    $scraper = new YouTubeScraper();
                    $channelPublished = $scraper->getChannelPublished($channelId);
                    $tableData['published_at'] = $channelPublished ?? date("Y-m-d H:i:s");
                }

                $channel->attributes = $tableData;
                $channel->save();

                $channelNotification = YtChannelNotification::findOne([
                        'channel_id' => $channelId, 'phone' => $phone
                    ]) ?? new YtChannelNotification();

                if (!empty($channelNotification->id)) {
                    Yii::$app->getSession()->setFlash('channel_notification_error', 'Notification already exists');
                } else {
                    $channelNotification->channel_id = $channelId;
                    $channelNotification->phone = $phone;
                    $channelNotification->save();
                    Yii::$app->getSession()->setFlash('channel_notification_success', 'Notification already added');
                }
            } else {
                Yii::$app->getSession()->setFlash('channel_notification_error', 'Channel not found');
            }
        }
    }
}

?>