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

    public $address;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['date','imei', 'address', 'is_deleted'], 'safe'],
        ];
    }

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
        /**
         *
         * Select date, imei, status, created_at, fireproof_counter_hrn, fireproof_counter_card from cb_log
        WHERE imei = imei
        Union all
        Select date, imei, status, created_at, Null as fireproof_counter_hrn, Null as fireproof_counter_card from wm_log
        ORDER by created_at
         */
//        $query = CbLog::find()
//            ->joinWith('WmLog');

        $null = 'Null';

//        $query1 = (new \yii\db\Query())
//            ->select('date, address_id, imei, status, created_at, fireproof_counter_hrn, fireproof_counter_card')
//            ->from('cb_log');
//
//        $query2 = (new \yii\db\Query())
//            ->select("date, address_id, imei, status, created_at, price AS fireproof_counter_hrn, spin_type AS fireproof_counter_card")
//            ->from('wm_log');

        $query1 = (new \yii\db\Query())
            ->select('*')
            ->from('cb_log');

        $query2 = (new \yii\db\Query())
            ->select('*')
            ->from('wm_log');

        $query = $query1->union($query2, false);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'date' => $this->date,
            'imei' => $this->imei,
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'imei', $this->imei]);

        return $dataProvider;
    }

//    /**
//     * @return array|null|\yii\db\ActiveRecord
//     */
//    public function getAddress($id)
//    {
//        return AddressBalanceHolder::find(['id' => $id])->one();
//    }
}
