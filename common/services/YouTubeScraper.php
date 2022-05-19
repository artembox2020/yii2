<?php

namespace common\services;

use yii\httpclient\Client;

class YouTubeScraper
{
    private $host = 'youtube-data-scraper.p.rapidapi.com';
    private $key = '9cc9c02892msh2313f80c6304a3ep1cd6c0jsnb66808adb073';

    public function getChannelInfo($channelId)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl("https://" . $this->host . "/channel/$channelId")
            ->addHeaders([
                'X-RapidAPI-Host' => $this->host,
                'X-RapidAPI-Key'  => $this->key,
            ])
            ->send();

        return $response->getData();
    }

    public function getChannelPublished($channelId)
    {
        $info = $this->getChannelInfo($channelId);

        if (empty($info['items'][0]['snippet']['publishedAt'])) {

            return null;
        }

        return $info['items'][0]['snippet']['publishedAt'];
    }
}