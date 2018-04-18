<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Imei;

/**
 * ImeiSearch represents the model behind the search form of `frontend\models\Imei`.
 */
class ImeiSearch extends Imei
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'imei', 'address_id', 'imei_central_board', 'critical_amount', 'time_out', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type_packet', 'firmware_version', 'type_bill_acceptance', 'serial_number_kp', 'phone_module_number', 'crash_event_sms', 'is_deleted'], 'safe'],
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
        $query = Imei::find()->where(['is_deleted' => false])->orWhere(['is_deleted' => null]);

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
            'imei' => $this->imei,
            'address_id' => $this->address_id,
            'imei_central_board' => $this->imei_central_board,
            'critical_amount' => $this->critical_amount,
            'time_out' => $this->time_out,
        ]);

        $query->andFilterWhere(['like', 'type_packet', $this->type_packet])
            ->andFilterWhere(['like', 'firmware_version', $this->firmware_version])
            ->andFilterWhere(['like', 'type_bill_acceptance', $this->type_bill_acceptance])
            ->andFilterWhere(['like', 'serial_number_kp', $this->serial_number_kp])
            ->andFilterWhere(['like', 'phone_module_number', $this->phone_module_number])
            ->andFilterWhere(['like', 'crash_event_sms', $this->crash_event_sms]);

        return $dataProvider;
    }
}
