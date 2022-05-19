<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

class PhraseSearch extends Phrase
{
    public function search($params)
    {
        $this->setScenario('SEARCH');
        $query = Phrase::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'phrase'         => $this->phrase,
            'lang'           => $this->lang,
            //'sid'        => Yii::$app->session->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);
        $query->orderBy(['created_at' => SORT_DESC]);

        /*$query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'descr', $this->descr])
            ->andFilterWhere(['like', 'text', $this->text]);*/

        return $dataProvider;
    }
}