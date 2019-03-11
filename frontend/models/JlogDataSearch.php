<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use frontend\models\Jlog;
use frontend\models\JlogDataCpSearch;
use frontend\models\WmMashine;
use frontend\models\Imei;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use frontend\services\parser\CParser;

/**
 * JlogDataSearch represents the model behind the search form of `frontend\models\JlogSearch`.
 */
class JlogDataSearch extends JlogSearch
{
    const TYPE_PAGE_SIZE = 10;
    const TYPE_QUERY_RECORDS_LIMIT = 500;

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param yii\db\ActiveDbQuery $query
     *
     * @return ArrayDataProvider
     */
    public function searchData($params, $query)
    {
        $query = $this->makeOrder($query, $params);
        $query = $query->limit(self::TYPE_QUERY_RECORDS_LIMIT);

        $dataInfo = [];
        $parser = new CParser();
        $typeMashine = $this->getMashineType();
        $numberMashine = $this->getMashineNumber();
        global $factorRelevance;
        $factorRelevance = 0;

        foreach ($query->all() as $item) {
            $mashinesInfo = $parser->getMashineData($item->packet);

            if (array_key_exists('WM', $mashinesInfo)) {

                $mashinesInfo = $mashinesInfo['WM'];

                if (!empty($typeMashine) && $typeMashine != 'WM') {
                    continue;
                }

                foreach ($mashinesInfo as $mashineItem) {

                    if (!empty($numberMashine) && $numberMashine != $mashineItem[1]) {
                        continue;
                    }

                    if ($this->applyFilterByNumberDeviceValue($params, $mashineItem)
                        &&
                        $this->applyFilterByNumberDeviceCondition($params, $mashineItem)
                    ) {

                        $dataInfo[] = [
                            'date' => $item->date,
                            'date_end' => $item->date_end,
                            'address' => $item->address,
                            'number_device' => $this->getNumberDeviceValue($mashineItem),
                            'level_signal' => $mashineItem[2],
                            'bill_cash' => $mashineItem[3],
                            'current_status' => $mashineItem[6],
                            'unix_time_offset' => $item->unix_time_offset,
                            //'display' => $mashineItem[7]
                        ];
                    } else {
                        ++$factorRelevance;
                    }
                }
            }
        }

        $countDataInfo = count($dataInfo);

        // factorRelevance is used to estimate the total number of records

        if (!empty($countDataInfo)) {
            $factorRelevance = $countDataInfo / ($countDataInfo + $factorRelevance);
        } else {
            $factorRelevance = 0;
        }

        // add conditions that should always apply here

        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataInfo,
            'pagination' => [
                'pageSize' => $params['page_size'] ? $params['page_size'] : self::PAGE_SIZE
            ],
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
                'attributes' => [
                    'date', 'address', 'number_device'
                ]
            ]
        ]);

        $dataProvider->sort->attributes['date'] = [
            'asc' => ['unix_time_offset' => SORT_ASC],
            'desc' => ['unix_time_offset' => SORT_DESC],
        ];

        return $dataProvider;
    }

    /**
     * Gets date start view 
     * 
     * @param JLogDataSearch $model
     * @return string
     */
    public function getDateStartView($model)
    {
        $jlogDataCpSearch = new JLogDataCpSearch();

        return $jlogDataCpSearch->getDateStartView((object)$model);
    }

    /**
     * Gets date end view 
     * 
     * @param JLogDataSearch $model
     * @return string
     */
    public function getDateEndView($model)
    {
        $jlogDataCpSearch = new JLogDataCpSearch();

        return $jlogDataCpSearch->getDateEndView((object)$model);
    }

    /**
     * Gets address view 
     * 
     * @param JLogDataSearch $model
     * @return string
     */
    public function getAddressView($model)
    {
        $jlogDataCpSearch = new JLogDataCpSearch();

        return $jlogDataCpSearch->getAddressView((object)$model);
    }

    /**
     * Gets summary message 
     * 
     * @param array $params
     * @param yii\db\ActiveQuery $query
     * @return string
     */
    public function getSummaryMessage($params, $query)
    {
        if (empty($params['page'])) {
            $params['page'] = 1;
        }

        $pageSize = $params['page_size'] ? $params['page_size'] : self::TYPE_PAGE_SIZE;

        $start =((int)$params['page'] - 1) * $pageSize + 1;
        $end = $start + $pageSize - 1;

        $totalNumber = (integer)($query->count() * $this->getAverageNumberOfWmMashinesPerImei($params));

        return Yii::$app->view->render(
            '/journal/data/summary-shapter-for-status', 
            ['start' => $start, 'end' => $end, 'totalNumber' => $totalNumber]
        );
    }

    /**
     * Gets WM Mashine current status 
     * 
     * @param JLogDataSearch $model
     * @return string|bool
     */
    public function getWmMashineStatus($model)
    {
        $statusCode = $model['current_status'];
        $wmMashine = new WmMashine();
        $wmMashineStatuses = $wmMashine->current_state;
        if (!is_int($statusCode) || !in_array($statusCode, array_keys($wmMashineStatuses))) {

            return false;
        }

        return Yii::t('frontend', $wmMashineStatuses[$statusCode]);
    }

    /**
     * Gets query assuming sorting 
     * 
     * @param ActiveDbQuery $query
     * @param array $params
     * @return ActiveDbQuery
     */
    public function makeOrder($query, $params)
    {
        switch ($params['sort']) {
            case '-date':
                $query = $query->orderBy(['unix_time_offset' => SORT_DESC]);
                break;
            case 'date':
                $query = $query->orderBy(['unix_time_offset' => SORT_ASC]);
                break;
            case '-address':
                $query = $query->orderBy(['address' => SORT_DESC]);
                break;
            case 'address':
                $query = $query->orderBy(['address' => SORT_ASC]);
                break;
            default:
                $query = $query->orderBy(['unix_time_offset' => SORT_DESC]);
                break;
        }

        return $query;
    }

    /**
     * Gets mashine type
     *
     * @return string|bool
     */
    public function getMashineType()
    {
        if (!empty($this->mashineNumber)) {

            return trim(substr(explode("*", $this->mashineNumber)[0], 1));
        }

        return false;
    }

    /**
     * Gets mashine number 
     *
     * @return string|bool
     */
    public function getMashineNumber()
    {
        if (!empty($this->mashineNumber)) {

            return trim(explode("*", $this->mashineNumber)[1]);
        }

        return false;
    }

    /**
     * Gets number device value string representation 
     * 
     * @param array $mashineItem
     * @return string
     */
    public function getNumberDeviceValue($mashineItem)
    {

        return Yii::t('frontend', 'WM').$mashineItem[1];
    }

    /**
     * Gets the average number of Wm Mashines per imei 
     * 
     * @param array $params
     * @return int
     */
    public function getAverageNumberOfWmMashinesPerImei($params)
    {
        global $factorRelevance;

        if (empty($factorRelevance)) {
            $factorRelevance = 1;
        }

        if (!empty($params['imei'])) {
            $imei = Imei::find()->andWhere(['like', 'imei', $params['imei']])->one();
        }

        // in case of defined imei find the number of mashines for it

        if (!empty($imei)) {
            $mashinesQuery = WmMashine::find()->andWhere(['imei_id' => $imei->id]);

            if (
                !empty($typeMashine = $this->getMashineType()) &&
                !empty($numberMashine = $this->getMashineNumber())
            ) {
                $mashinesQuery = $mashinesQuery->andWhere(['type_mashine' => $typeMashine, 'number_device' => $numberMashine]);
            }

            $numberOfWmMashines = $mashinesQuery->count();

            if ($numberOfWmMashines != 0) {

                return $numberOfWmMashines * $factorRelevance;
            }
        } else {

            // find division numberOfWmMashines/numberOfImeis

            $numberOfImeis = Imei::find()->count();
            $numberOfWmMashines = WmMashine::find()->count();

            if ($numberOfImeis != 0) {

                return (double)$numberOfWmMashines / $numberOfImeis * $factorRelevance;
            }
        }

        return 0;
    }

    /**
     * Applies filter by number device value 
     * 
     * @param array $params
     * @param  array $mashineItem
     * @return bool
     */
    public function applyFilterByNumberDeviceValue($params, $mashineItem)
    {
        if (empty($params['inputValue']['number_device'])) {

            return true;
        }

        $numberDeviceValue = $this->getNumberDeviceValue($mashineItem);

        return mb_stripos($numberDeviceValue, $params['inputValue']['number_device']) !== false;
    }

    /**
     * Applies filter by number device condition 
     * 
     * @param array $params
     * @param  array $mashineItem
     * @return bool
     */
    public function applyFilterByNumberDeviceCondition($params, $mashineItem)
    {
        $numberDeviceValue = $this->getNumberDeviceValue($mashineItem);
        $param = trim($params['val1']['number_device']);
        switch($params['filterCondition']['number_device']) {
            case self::FILTER_NOT_SET:

                return true;
            case self::FILTER_CELL_EMPTY:

                return empty($numberDeviceValue);
            case self::FILTER_CELL_NOT_EMPTY:

                return !empty($numberDeviceValue);
            case self::FILTER_TEXT_CONTAIN:

                return mb_stripos($numberDeviceValue, $param) !== false;   
            case self::FILTER_TEXT_NOT_CONTAIN:
               
                return mb_stripos($numberDeviceValue, $param) === false; 
            case self::FILTER_TEXT_START_FROM:

                return mb_stripos($numberDeviceValue, $param) === 0;
            case self::FILTER_TEXT_END_WITH:

                return 
                    mb_strripos($numberDeviceValue, $param) === mb_strlen($numberDeviceValue) - mb_strlen($param);
            case self::FILTER_TEXT_EQUAL:

                return $numberDeviceValue === $param;
        }

        return true;
    }
}
