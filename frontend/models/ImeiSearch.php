<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Imei;
use common\models\User;

/**
 * ImeiSearch represents the model behind the search form of `frontend\models\Imei`.
 */
class ImeiSearch extends Imei
{
    public $address;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'imei', 'address_id', 'imei_central_board', 'critical_amount', 'time_out', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type_packet', 'firmware_version', 'type_bill_acceptance', 'serial_number_kp', 'phone_module_number', 'crash_event_sms', 'is_deleted'], 'safe'],
            [['address'], 'trim']
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
        $query = Imei::find();
        $query = $query->andWhere(['imei.company_id' => $user->company_id]);

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
            'address_id' => $this->address_id,
        ]);
            
        $query->andFilterWhere(
            ['like', 'imei', $this->imei]
        );
        
        $query->join('LEFT JOIN', 'address_balance_holder', 'address_balance_holder.id = imei.address_id')
              ->andWhere(['like','address_balance_holder.address', $this->address]); 
        
        return $dataProvider;
    }
}
