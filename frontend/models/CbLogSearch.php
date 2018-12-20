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

    const INFINITY = 9999999999999999;

    public $from_date;
    public $to_date;
    public $mashineNumber;
    public $inputValue = ['date'];
    public $val2 = ['date'];

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
        $searchFilter = new CbLogSearchFilter();

        $query1 = (new \yii\db\Query())
            ->select('*')
            ->from('cb_log')->where(['company_id' => $entity->getCompanyId()]);

        $query2 = (new \yii\db\Query())
            ->select('*')
            ->from('wm_log')->where(['company_id' => $entity->getCompanyId()]);

        $query = (new \yii\db\Query())
            ->from(['cb_log' => $query1->union($query2)])
            ->orderBy(['date' => SORT_DESC]);

        $query = $searchFilter->applyFilterByValueMethod($query, 'address', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'address', $params, CbLogSearchFilter::FILTER_CATEGORY_COMMON);

        $query = $searchFilter->applyFilterByValueMethod($query, 'date', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'date', $params, CbLogSearchFilter::FILTER_CATEGORY_DATE);

        $query = $searchFilter->applyFilterByValueMethod($query, 'imei', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'imei', $params, CbLogSearchFilter::FILTER_CATEGORY_COMMON);

        $query = $searchFilter->applyFilterByValueMethod($query, 'rate', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'rate', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC);

        $query = $searchFilter->applyFilterByValueMethod($query, 'device', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'device', $params, CbLogSearchFilter::FILTER_CATEGORY_COMMON);

        $query = $searchFilter->applyFilterByValueMethod($query, 'signal', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'signal', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC);

        $query = $searchFilter->applyFilterByValueMethod($query, 'fireproof_counter_hrn', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'fireproof_counter_hrn', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC);

        $query = $searchFilter->applyFilterByValueMethod($query, 'collection_counter', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'collection_counter', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC);

        $query = $searchFilter->applyFilterByValueMethod($query, 'notes_billiards_pcs', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'notes_billiards_pcs', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC);

        $query = $searchFilter->applyFilterByValueMethod($query, 'wash_temperature', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'wash_temperature', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC);

        $query = $searchFilter->applyFilterByValueMethod($query, 'address', ['inputValue' => $params]);
        $query = $searchFilter->applyFilterByValueMethod($query, 'imei', ['inputValue' => $params]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ],
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

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function getEncashment($params)
    {
        $entity = new Entity();
        $searchFilter = new CbLogSearchFilter();

        $query = (new \yii\db\Query())
            ->select(['date',
                'address_id',
                'collection_counter',
                'unix_time_offset',
                'fireproof_counter_hrn'])
            ->from('cb_log')->where(['company_id' => $entity->getCompanyId()]);

        $query->orderBy([
//                'id' => SORT_ASC,
                'date' => SORT_DESC,
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ],
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
    
    public function setParams($searchModel, $params, $prms)
    {
        $jlogSearchModel = new JlogSearch();

        return $jlogSearchModel->setParams($searchModel, $params, $prms);
    }
}
