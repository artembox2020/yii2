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

        $wmLogQuery = (new \yii\db\Query())
            ->select([
                'unix_time_offset', 
                'address_id', 
                'imei', 
                'device',
                'number',
                'signal',
                'status',
                'price',
                'account_money',
                'washing_mode',
                'wash_temperature',
                'spin_type',
                'prewash',
                'rinsing',
                'intensive_wash'
            ])
            ->from('wm_log')
            ->where(['company_id' => $entity->getCompanyId()]);

        $wmLogQuery = $this->applyCommonFilters($wmLogQuery, $params);

        $wmLogQuery = $searchFilter->applyFilterByConditionMethod(
            $wmLogQuery, 'number', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC
        );

        $cbLogQuery = (new \yii\db\Query())
            ->select([
                'unix_time_offset',
                'address_id',
                'imei',
                'device',
                'number',
                'signal',
                'status',
                'rate AS price',
                'account_money',
                'notes_billiards_pcs AS washing_mode',
                'fireproof_counter_hrn AS wash_temperature',
                'collection_counter AS spin_type',
                'last_collection_counter AS prewash', 
                'rinsing',
                'intensive_wash'
            ])
            ->from('cb_log')
            ->where(['company_id' => $entity->getCompanyId()]);

        $cbLogQuery = $this->applyCommonFilters($cbLogQuery, $params);
        $cbLogQuery = $searchFilter->applyFilterByValueMethod($cbLogQuery, 'number', $params);

        $cbLogQuery->union($wmLogQuery);

        $query = new \yii\db\Query();
        $query->select('*')->from(['u' => $cbLogQuery])->orderBy(['unix_time_offset' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ],
            'sort' => [
                'defaultOrder' => ['unix_time_offset' => SORT_DESC],
                'attributes' => ['unix_time_offset']
            ]
        ]);

        $this->load($params);

        return $dataProvider;
    }

    /**
     * Apply filters common for both `wm_log` and `cb_log` tables
     *
     * @param Query $query
     * @param array $params
     *
     * @return Query
     */
    public function applyCommonFilters($query, $params)
    {
        $searchFilter = new CbLogSearchFilter();

        $query = $this->applyBetweenDateCondition($query);

        $query = $searchFilter->applyFilterByValueMethod($query, 'address', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'address', $params, CbLogSearchFilter::FILTER_CATEGORY_COMMON);

        $query = $searchFilter->applyFilterByValueMethod($query, 'date', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'date', $params, CbLogSearchFilter::FILTER_CATEGORY_DATE);

        $query = $searchFilter->applyFilterByValueMethod($query, 'imei', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'imei', $params, CbLogSearchFilter::FILTER_CATEGORY_COMMON);

        $query = $searchFilter->applyFilterByValueMethod($query, 'imei', ['inputValue' => $params]);

        $query = $searchFilter->applyFilterByValueMethod($query, 'address', ['inputValue' => $params]);

        $query = $query->andFilterWhere(['number' => $params['wm_mashine_number']]);

        return $query;
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

    /**
     * Sets basic parameters
     * 
     * @param CbLogSearch $searchModel
     * @param array $params
     * @param array $prms
     * @return array
     */
    public function setParams($searchModel, $params, $prms)
    {
        $jlogSearchModel = new JlogSearch();

        return $jlogSearchModel->setParams($searchModel, $params, $prms);
    }

    /**
     * Gets 'device' basing on model data 
     * 
     * @param array $model
     * @return string
     */ 
    public function getDeviceView($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return Yii::t('logs', strtoupper($model['device'])).' '.$model['number'];
        }

        return Yii::t('logs', strtoupper($model['device']));
    }
    
    /**
     * Gets 'signal' or 'price' basing on model data 
     * 
     * @param array $model
     * @return integer
     */ 
    public function getLevelSignalView($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $model['signal'];
        }

        return $model['price'];
    }

    /**
     * Gets 'status' basing on model data 
     * 
     * @param array $model
     * @return integer
     */ 
    public function getStatus($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $this->getWmStatus($model);
        }

        return $this->getCpStatus($model);
    }

    /**
     * Gets 'price' basing on model data 
     * 
     * @param array $model
     * @return integer|null
     */ 
    public function getPrice($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $model['price'];
        }

        return null;
    }

    /**
     * Gets 'account_money' basing on model data 
     * 
     * @param array $model
     * @return integer|null
     */ 
    public function getAccountMoney($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $model['account_money'];
        }

        return null;
    }

    /**
     * Gets 'notes_billiards_pcs' or 'washing_mode' basing on model data 
     * 
     * @param array $model
     * @return integer
     */    
    public function getNotesBilliardsPcs($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $this->getWashingMode($model);
        }

        return $model['washing_mode'];
    }

    /**
     * Gets 'fireproof_counter_hrn' basing on model data 
     * 
     * @param array $model
     * @return double|bool
     */
    public function getFireproofCounterHrn($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $this->getWashTemperature($model);
        }

        return round($model['wash_temperature'], 1);
    }

    /**
     * Gets 'collection_counter' basing on model data 
     * 
     * @param array $model
     * @return double|bool
     */
    public function getCollectionCounter($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $this->getSpinType($model);
        }

        return round($model['spin_type'], 1);
    }

    /**
     * Gets 'last_collection_counter' basing on model data 
     * 
     * @param array $model
     * @return double|string|bool
     */
    public function getLastCollectionCounter($model)
    {
        if (strtoupper($model['device']) == 'WM') {

            return $this->getAdditionalWashOptions($model);
        }

        return round($model['prewash'], 1);
    }

    /**
     * Gets wm mashine log status basing on model data 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getWmStatus($model)
    {
        $machine = new \frontend\models\WmMashine();
        if (array_key_exists($model['status'], $machine->log_state)) {

            return Yii::t('logs', $machine->log_state[$model['status']]);
        }

        return false;
    }

    /**
     * Gets cp status basing on model data 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getCpStatus($model)
    {
        $cbLog = new \frontend\models\CbLog();

        if (array_key_exists($model['status'], $cbLog->current_state)) {

            return Yii::t('logs', $cbLog->current_state[$model['status']]);
        }

        return false;
    }

    /**
     * Gets 'washing_mode' basing on model data 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getWashingMode($model)
    {
        $machine = new \frontend\models\WmMashine();
        if (array_key_exists($model['washing_mode'], $machine->washing_mode)) {

            return Yii::t('logs', $machine->washing_mode[$model['washing_mode']]);
        }

        return false;
    }

    /**
     * Gets 'wash_temperature' basing on model data 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getWashTemperature($model)
    {
        $machine = new \frontend\models\WmMashine();

        if (array_key_exists($model['wash_temperature'], $machine->wash_temperature)) {

            return Yii::t('logs', $machine->wash_temperature[$model['wash_temperature']]);
        }

        return false;
    }

    /**
     * Gets 'spin_type' basing on model data 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getSpinType($model)
    {
        $machine = new \frontend\models\WmMashine();

        if (array_key_exists($model['spin_type'], $machine->spin_type)) {

            return Yii::t('logs', $machine->spin_type[$model['spin_type']]);
        }

        return false;
    }

    /**
     * Gets additional options(prewash,rinsing,intensive_wash) basing on model data 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getAdditionalWashOptions($model)
    {
         if ($model['prewash'] == 1) {
            $prewash = 'prewash';
         } else {
            $prewash = '';
        }

        if ($model['rinsing'] == 1) {
            $rinsing = 'rinsing';
        } else {
            $rinsing = '';
        }

        if($model['intensive_wash'] == 1) {
            $intensive_wash = 'intensive_wash';
        } else {
            $intensive_wash = '';
        }

        return Yii::t('logs', $prewash) . ' 
            ' . Yii::t('logs', $rinsing) . ' 
            ' . Yii::t('logs', $intensive_wash);
    }

    /**
     * Gets address string representation basing on model data 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getAddressView($model)
    {
        if (
            empty($model['address_id']) ||
            ! ($address = AddressBalanceHolder::findOne($model['address_id']))
        ) {

            return false;
        }

        $address = $address->address.', '.$address->floor;
        $model = (object)$model;
        $model->address = $address;
        $jlogInitSearch = new JlogInitSearch();

        return  $jlogInitSearch->getAddressView($model);
    }

    /**
     * Applies between date condition to query 
     * 
     * @param ActiveDbQuery $query
     * @return ActiveDbQuery
     */
    public function applyBetweenDateCondition($query)
    {
        $timeFrom = 0;
        $timeTo = self::INFINITY;
        $startDay = ' 00:00:00';
        $endDay = ' 23:59:59';

        if (!empty($this->from_date)) {

            if (!strrpos($this->from_date, $startDay)) {
                $this->from_date .= $startDay;
            }

            $timeFrom = strtotime($this->from_date) - Jlog::TYPE_TIME_OFFSET;
        }

        if (!empty($this->to_date)) {

            if (!strrpos($this->to_date, $endDay)) {
                $this->to_date .= $endDay;
            }

            $timeTo = strtotime($this->to_date) - Jlog::TYPE_TIME_OFFSET;
        }

        $betweenCondition = new \yii\db\conditions\BetweenCondition(
            'unix_time_offset', 
            'BETWEEN',
            $timeFrom,
            $timeTo
        );

        $query = $query->andWhere($betweenCondition);

        return $query;
    }

}
