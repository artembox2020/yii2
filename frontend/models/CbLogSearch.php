<?php

namespace frontend\models;

use frontend\services\custom\Debugger;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CbLogSearch
 * @package frontend\models
 */
class CbLogSearch extends CbLog
{
    const PAGE_SIZE = 10;

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
//        $imei = Imei::findOne(Yii::$app->user->id);
        $query = CbLog::find();

//        $query = $query->andWhere(['imei_id' => 15]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ]
        ]);

        $this->load($params);
//
//
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'imei', $this->imei]);

        return $dataProvider;

    }
}
