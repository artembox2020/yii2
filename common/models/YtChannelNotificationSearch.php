<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

class YtChannelNotificationSearch extends YtChannelNotification
{
    public function search($params)
    {
        $this->setScenario('SEARCH');
        $query = YtChannelNotification::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $this->load($params, 'YtChannelNotificationSearch');
        $this->load($params, 'DynamicModel');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'channel_id', $this->channel_id])
            ->andFilterWhere(['like', 'phone', $this->phone])
        ;

        return $dataProvider;
    }
}