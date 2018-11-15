<?php

namespace frontend\models;

use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
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
        $entity = new Entity();
        $query1 = (new \yii\db\Query())
            ->select('*')
            ->from('cb_log')->where(['company_id' => $entity->getCompanyId()]);

        $query2 = (new \yii\db\Query())
            ->select('*')
            ->from('wm_log')->where(['company_id' => $entity->getCompanyId()]);

        $query = (new \yii\db\Query())
            ->from(['cb_log' => $query1->union($query2)])
            ->orderBy(['date' => SORT_DESC]);

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
