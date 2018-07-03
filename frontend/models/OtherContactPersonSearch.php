<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\OtherContactPerson;
use yii\helpers\ArrayHelper;

/**
 * OtherContactPersonSearch represents the model behind the search form of `frontend\models\OtherContactPerson`.
 */
class OtherContactPersonSearch extends OtherContactPerson
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'balance_holder_id', 'created_at', 'deleted_at'], 'integer'],
            [['name', 'position', 'phone', 'is_deleted'], 'safe'],
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
     * @param User $model
     *
     * @return ActiveDataProvider
     */
    public function search($params, $model)
    {
        $balanceHolderIds = ArrayHelper::getColumn($model->company->balanceHolders, 'id');
        $query = OtherContactPerson::find()->andWhere(['balance_holder_id' => array_values($balanceHolderIds)]);

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
            'balance_holder_id' => $this->balance_holder_id,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
