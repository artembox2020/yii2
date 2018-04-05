<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ImeiData;

/**
 * ImeiDataSearch represents the model behind the search form of `frontend\models\ImeiData`.
 */
class ImeiDataSearch extends ImeiData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'imei_id', 'created_at', 'imei', 'level_signal', 'on_modem_account', 'in_banknotes', 'money_in_banknotes', 'fireproof_residue', 'price_regim', 'updated_at', 'deleted_at'], 'integer'],
            [['is_deleted'], 'safe'],
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
        $query = ImeiData::find();

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
            'imei_id' => $this->imei_id,
            'created_at' => $this->created_at,
            'imei' => $this->imei,
            'level_signal' => $this->level_signal,
            'on_modem_account' => $this->on_modem_account,
            'in_banknotes' => $this->in_banknotes,
            'money_in_banknotes' => $this->money_in_banknotes,
            'fireproof_residue' => $this->fireproof_residue,
            'price_regim' => $this->price_regim,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
