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

        $query = (new \yii\db\Query())
            ->select('*')
            ->from('wm_log')
            ->where(['company_id' => $entity->getCompanyId()]);

        $query = $this->applyBetweenDateCondition($query);
        $query = $query->andFilterWhere(['number' => $params['wm_mashine_number']]);

        $query = $searchFilter->applyFilterByValueMethod($query, 'address', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'address', $params, CbLogSearchFilter::FILTER_CATEGORY_COMMON);

        $query = $searchFilter->applyFilterByValueMethod($query, 'date', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'date', $params, CbLogSearchFilter::FILTER_CATEGORY_DATE);

        $query = $searchFilter->applyFilterByValueMethod($query, 'imei', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'imei', $params, CbLogSearchFilter::FILTER_CATEGORY_COMMON);

        $query = $searchFilter->applyFilterByValueMethod($query, 'number', $params);
        $query = $searchFilter->applyFilterByConditionMethod($query, 'number', $params, CbLogSearchFilter::FILTER_CATEGORY_NUMERIC);

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

        $query = $searchFilter->applyFilterByValueMethod($query, 'imei', ['inputValue' => $params]);

        $query = $searchFilter->applyFilterByValueMethod($query, 'address', ['inputValue' => $params]);

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
     * Gets basic query joininig 'cb_log' and 'wm_log' tables
     * 
     * @param array $model
     * @return ActiveDbQuery
     */
    private function getBaseCbLogQuery($model)
    {

        return CbLog::find()->andWhere([
            'unix_time_offset' => $model['unix_time_offset'],
            'imei' => $model['imei']
        ]);
    }

    /**
     * Gets 'fireproof_counter_hrn' basing on model data 
     * 
     * @param array $model
     * @return double|bool
     */
    public function getFireproofCounterHrn($model)
    {
        $query = $this->getBaseCbLogQuery($model);
        $item = $query->limit(1)->one();

        if (!$item) {

            return false;
        }

        return $item->fireproof_counter_hrn;
    }

    /**
     * Gets 'notes_billiard_pcs' basing on model data 
     * 
     * @param array $model
     * @return integer|bool
     */
    public function getNotesBilliardsPcs($model)
    {
        $query = $this->getBaseCbLogQuery($model);
        $item = $query->limit(1)->one();
        
         if (!$item) {

            return false;
        }

        return $item->notes_billiards_pcs;
    }

    /**
     * Gets 'collection_counter' basing on model data 
     * 
     * @param array $model
     * @return double|bool
     */
    public function getCollectionCounter($model)
    {
        $query = $this->getBaseCbLogQuery($model);
        $item = $query->limit(1)->one();
        
         if (!$item) {

            return false;
        }

        return $item->collection_counter;
    }

    /**
     * Gets 'last_collection_counter' basing on model data 
     * 
     * @param array $model
     * @return double|bool
     */
    public function getLastCollectionCounter($model)
    {
        $query = $this->getBaseCbLogQuery($model);
        $item = $query->limit(1)->one();

         if (!$item) {

            return false;
        }

        return $item->last_collection_counter;
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
        $query = $this->getBaseCbLogQuery($model);
        $item = $query->limit(1)->one();

        if (!$item) {

            return false;
        }

        if (array_key_exists($item->status, $cbLog->current_state)) {

            return Yii::t('logs', $cbLog->current_state[$item->status]);
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
     * Gets aggregated wm mashine info data basing on model 
     * 
     * @param array $model
     * @return string|bool
     */
    public function getAggregatedEventsInfo($model)
    {
        $wmStatus = $this->getWmStatus($model);
        $washingMode = $this->getWashingMode($model);
        $washTemperature = $this->getWashTemperature($model);
        $spinType = $this->getSpinType($model);
        $additionalWashOptions = $this->getAdditionalWashOptions($model);
        $cpStatus = $this->getCpStatus($model);

        $content = Yii::$app->view->render(
            '/journal/logs/aggregated-status-info',
            [
                'model' => $model,
                'wmStatus' => $wmStatus,
                'washingMode' => $washingMode,
                'washTemperature' => $washTemperature,
                'spinType' => $spinType,
                'additionalWashOptions' => $additionalWashOptions,
                'cpStatus' => $cpStatus
            ]
        );

        return
            $wmStatus.
            \frontend\services\globals\EntityHelper::makePopupWindow(
                [],
                $content,
                'top: -300px; left: -6px; display: none; color: black; position: absolute;',
                'height: auto; position:relative;'
            );
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
