<?php

namespace frontend\models;

use frontend\services\custom\Debugger;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use frontend\models\AddressBalanceHolder;

/**
 * AddressBalanceHolderSearch represents the model behind the search form of `frontend\models\AddressBalanceHolder`.
 */
class AddressBalanceHolderSearch extends AddressBalanceHolder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'balance_holder_id', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['name', 'address', 'is_deleted'], 'safe'],
            [['floor'], 'trim']
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
        $user = User::findOne(Yii::$app->user->id);
        $query = AddressBalanceHolder::find();

        $query = $query->andWhere(['company_id' => $user->company->id]);
        
        if (!empty($params['balanceHolderId']))
            $query = $query->andWhere(['balance_holder_id' => $params['balanceHolderId']]);

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
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'floor', $this->floor])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
