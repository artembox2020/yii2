<?php

namespace frontend\models;

use common\models\User;
use frontend\services\custom\Debugger;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\WmMashine;

/**
 * WmMashineSearch represents the model behind the search form of `frontend\models\WmMashine`.
 */
class WmMashineSearch extends WmMashine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'imei_id', 'number_device', 'level_signal', 'bill_cash', 'door_position', 'door_block_led', 'status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type_mashine', 'is_deleted'], 'safe'],
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
        $query = WmMashine::find();

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
            'number_device' => $this->number_device,
            'level_signal' => $this->level_signal,
            'bill_cash' => $this->bill_cash,
            'door_position' => $this->door_position,
            'door_block_led' => $this->door_block_led,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'display' => $this->display,
            'inventory_number' => $this->inventory_number
        ]);

        $query->andFilterWhere(['like', 'type_mashine', $this->type_mashine])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchWashMachine($params)
    {
        $user = User::findOne(Yii::$app->user->id);

        $query = WmMashine::find()
            ->andOnCondition(['wm_mashine.is_deleted' => false])
            ->andOnCondition(['company_id' => $user->company_id])
            ->andWhere(['status' => WmMashine::STATUS_ACTIVE])
            ->orWhere(['status' => WmMashine::STATUS_OFF]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'type_mashine', $this->type_mashine])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
