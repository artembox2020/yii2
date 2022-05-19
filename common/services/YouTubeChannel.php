<?php

namespace common\services;

use Yii;

class YouTubeChannel
{
    private $googleApiKey;
    private $googleBaseApiUrl;

    public function __construct()
    {
        $this->googleBaseApiUrl = Yii::$app->params['google.youtube_api_base_url'];
        $this->googleApiKey = Yii::$app->params['google.api_key'];
    }

    function getList($channelId, $playlistId = false, $searchOptions = [], $pageToken = false, $maxResults = 50): array
    {
        $queryParams= http_build_query(
            array_merge(
                [
                    'channelId' => $channelId,
                    'order' => 'date',
                    'part' => 'snippet',
                    'type' => 'video',
                    'maxResults' => $maxResults,
                    'key' => $this->googleApiKey,
                ],
                $searchOptions
            )
        );

        if ($pageToken) {
            $queryParams .= "&pageToken=$pageToken";
        }

        $url = $this->googleBaseApiUrl . '/search?' . $queryParams;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function getListByPlaylist($channelId, $playlistId, $searchOptions = [], $pageToken = false, $maxResults = 50)
    {
        $queryParams= http_build_query([
            'playlistId' => $playlistId,
            'part' => 'snippet',
            'maxResults' => $maxResults,
            'key' => $this->googleApiKey,
        ]);

        if ($pageToken) {
            $queryParams .= "&pageToken=$pageToken";
        }

        $url = $this->googleBaseApiUrl . '/playlistItems?' . $queryParams;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function getDetails($channelId)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->googleBaseApiUrl . "/channels?part=contentDetails,statistics,brandingSettings&id={$channelId}&key=" . $this->googleApiKey,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['success' => false, 'message' => $err];
        } else {
            return array_merge(['success' => true], json_decode($response, true));
        }
    }

    public function getChannelTableData($channelId)
    {
        $list = $this->getList($channelId, false, [], false, 1);

        if (empty($list['items'][0]['id'])) {

            return [];
        }

        $details = $this->getDetails($channelId);

        if (empty($details['items'][0]['statistics'])) {

            return [];
        }

        $postCount = $details['items'][0]['statistics']['videoCount'];
        $lastPost = $list['items'][0]['id']['videoId'];
        $brandingSettings = $details['items'][0]['brandingSettings']['channel'];

        $data = [
            'id'    => $channelId,
            'title' => $brandingSettings['title'],
            'descr' => !empty($brandingSettings['description']) ? $brandingSettings['description'] : null,
            'post_count'   => $postCount,
            'last_post'    => $lastPost,
        ];

        return $data;
    }
}