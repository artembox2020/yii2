<?php

namespace backend\models\search;

use frontend\services\custom\Debugger;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    const ONE = 1;
    const THIRTY = 30;

    public $company;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'action_at'], 'integer'],
            [['username', 'email', 'ip', 'company'], 'safe'],
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $query->joinWith(['company']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pagesize' => self::THIRTY],
        ]);

        $dataProvider->sort->attributes['company'] = [
            'asc' => ['company.name' => SORT_ASC],
            'desc' => ['company.name' => SORT_DESC],
        ];

//        $this->load($params);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'action_at' => $this->action_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'company.name', $this->company])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }

    public function searchDeleted($params)
    {
//        $query = Company::find();

        $query = User::find()->where(['is_deleted' => self::ONE]);

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
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'action_at' => $this->action_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
