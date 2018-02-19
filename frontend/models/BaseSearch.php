<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Base;

/**
 * BaseSearch represents the model behind the search form of `frontend\models\Base`.
 */
class BaseSearch extends Base
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date', 'imei', 'gsmSignal', 'fvVer', 'numBills', 'billAcceptorState', 'id_hard', 'type', 'collection', 'ZigBeeSig', 'billCash', 'tariff', 'event', 'edate', 'billModem', 'sumBills', 'ost', 'numDev', 'devSignal', 'statusDev', 'colGel', 'colCart', 'price', 'timeout', 'doorpos', 'doorled', 'kpVer', 'srVer', 'mTel', 'sTel', 'ksum'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
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
        $query = Base::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'imei', $this->imei])
            ->andFilterWhere(['like', 'gsmSignal', $this->gsmSignal])
            ->andFilterWhere(['like', 'fvVer', $this->fvVer])
            ->andFilterWhere(['like', 'numBills', $this->numBills])
            ->andFilterWhere(['like', 'billAcceptorState', $this->billAcceptorState])
            ->andFilterWhere(['like', 'id_hard', $this->id_hard])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'collection', $this->collection])
            ->andFilterWhere(['like', 'ZigBeeSig', $this->ZigBeeSig])
            ->andFilterWhere(['like', 'billCash', $this->billCash])
            ->andFilterWhere(['like', 'tariff', $this->tariff])
            ->andFilterWhere(['like', 'event', $this->event])
            ->andFilterWhere(['like', 'edate', $this->edate])
            ->andFilterWhere(['like', 'billModem', $this->billModem])
            ->andFilterWhere(['like', 'sumBills', $this->sumBills])
            ->andFilterWhere(['like', 'ost', $this->ost])
            ->andFilterWhere(['like', 'numDev', $this->numDev])
            ->andFilterWhere(['like', 'devSignal', $this->devSignal])
            ->andFilterWhere(['like', 'statusDev', $this->statusDev])
            ->andFilterWhere(['like', 'colGel', $this->colGel])
            ->andFilterWhere(['like', 'colCart', $this->colCart])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'timeout', $this->timeout])
            ->andFilterWhere(['like', 'doorpos', $this->doorpos])
            ->andFilterWhere(['like', 'doorled', $this->doorled])
            ->andFilterWhere(['like', 'kpVer', $this->kpVer])
            ->andFilterWhere(['like', 'srVer', $this->srVer])
            ->andFilterWhere(['like', 'mTel', $this->mTel])
            ->andFilterWhere(['like', 'sTel', $this->sTel])
            ->andFilterWhere(['like', 'ksum', $this->ksum]);

        return $dataProvider;
    }
}
