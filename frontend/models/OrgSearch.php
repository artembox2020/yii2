<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Org;

/**
 * OrgSearch represents the model behind the search form of `frontend\models\Org`.
 */
class OrgSearch extends Org
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'admin_id'], 'integer'],
            [['name_org', 'logo_path', 'desc'], 'safe'],
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
        $query = Org::find();

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
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
        ]);

        $query->andFilterWhere(['like', 'name_org', $this->name_org])
            ->andFilterWhere(['like', 'logo_path', $this->logo_path])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
