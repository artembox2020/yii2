<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Jlog;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;

/**
 * JlogSearch represents the model behind the search form of `frontend\models\Jlog`.
 */
class JlogSearch extends Jlog
{
    const PAGE_SIZE = 10;

    const FILTER_NOT_SET = 0;
    const FILTER_CELL_EMPTY = 1;
    const FILTER_CELL_NOT_EMPTY = 2;
    const FILTER_TEXT_CONTAIN = 3;
    const FILTER_TEXT_NOT_CONTAIN = 4;
    const FILTER_TEXT_START_FROM = 5;
    const FILTER_TEXT_END_WITH = 6;
    const FILTER_TEXT_EQUAL = 7;

    const FILTER_DATE = 8;
    const FILTER_DATE_BEFORE = 9;
    const FILTER_DATE_AFTER = 10;

    const FILTER_MORE = 11;
    const FILTER_MORE_EQUAL = 12;
    const FILTER_LESS = 13;
    const FILTER_LESS_EQUAL = 14;
    const FILTER_EQUAL = 15;
    const FILTER_NOT_EQUAL = 16;
    const FILTER_BETWEEN = 17;
    const FILTER_NOT_BETWEEN = 18;
    
    const FILTER_CATEGORY_COMMON = 19;
    const FILTER_CATEGORY_DATE = 20;
    const FILTER_CATEGORY_NUMERIC = 21;

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
        $entity = new Entity();
        $entityHelper = new EntityHelper();
        $query = $entity->getUnitsQueryPertainCompany(new Jlog());

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ]
        ]);

        $dataProvider->sort->attributes['date'] = [
            'asc' => ['STR_TO_DATE(j_log.date, \''.Imei::MYSQL_DATE_TIME_FORMAT.'\')' => SORT_ASC],
            'desc' => ['STR_TO_DATE(j_log.date, \''.Imei::MYSQL_DATE_TIME_FORMAT.'\')' => SORT_DESC],
        ];

        $this->load($params);

        // apply filters by id column

        $query = $this->applyFilterByValueMethod($query, 'id', $params);
        $query = $this->applyFilterByConditionMethod($query, 'id', $params, self::FILTER_CATEGORY_NUMERIC);

        // apply filters by type_packet column

        $query = $this->applyFilterByValueMethod($query, 'type_packet', $params);
        $query = $this->applyFilterByConditionMethod($query, 'type_packet', $params, self::FILTER_CATEGORY_COMMON);

        // apply filters by date column 

        $query = $this->applyFilterByValueMethod($query, 'date', $params);
        $query = $this->applyFilterByConditionMethod($query, 'date', $params, self::FILTER_CATEGORY_DATE);

        // apply filters by address column

        $query = $this->applyFilterByValueMethod($query, 'address', $params);
        $query = $this->applyFilterByConditionMethod($query, 'address', $params, self::FILTER_CATEGORY_COMMON);

        // apply filters by imei column

        $query = $this->applyFilterByValueMethod($query, 'imei', $params);
        $query = $this->applyFilterByConditionMethod($query, 'imei', $params, self::FILTER_CATEGORY_COMMON);

        // grid filtering conditions

        if (!empty($params['type_packet'])) {
            $query->andFilterWhere([
                'type_packet' => $params['type_packet'],
            ]);
        }

        $query->andFilterWhere(['like', 'imei', $params['imei']]);

        $query->andFilterWhere(['like', 'address', $params['address']]);

        return $dataProvider;
    }

    /**
     * Gets array of common filters
     * 
     * @return array
     */
    public static function getCommonFilters()
    {
        
        return [
            self::FILTER_NOT_SET => Yii::t('frontend', 'FILTER NOT SET'),
            self::FILTER_CELL_EMPTY => Yii::t('frontend', 'FILTER CELL EMPTY'),
            self::FILTER_CELL_NOT_EMPTY => Yii::t('frontend', 'FILTER CELL NOT EMPTY'),
            self::FILTER_TEXT_CONTAIN => Yii::t('frontend', 'FILTER TEXT CONTAIN'),
            self::FILTER_TEXT_NOT_CONTAIN => Yii::t('frontend', 'FILTER TEXT NOT CONTAIN'),
            self::FILTER_TEXT_START_FROM => Yii::t('frontend', 'FILTER TEXT START FROM'),
            self::FILTER_TEXT_END_WITH => Yii::t('frontend', 'FILTER TEXT END WITH'),
            self::FILTER_TEXT_EQUAL => Yii::t('frontend', 'FILTER TEXT EQUAL')
        ];
    }

    /**
     * Gets array of date filters
     * 
     * @return array
     */
    public static function getDateFilters()
    {
        
        return [
            self::FILTER_NOT_SET => Yii::t('frontend', 'FILTER NOT SET'),
            self::FILTER_DATE => Yii::t('frontend', 'FILTER DATE'),
            self::FILTER_DATE_BEFORE => Yii::t('frontend', 'FILTER DATE BEFORE'),
            self::FILTER_DATE_AFTER => Yii::t('frontend', 'FILTER DATE AFTER')
        ];
    }

    /**
     * Gets array of numeric filters
     * 
     * @return array
     */
    public static function getNumericFilters()
    {
        
        return [
            self::FILTER_NOT_SET => Yii::t('frontend', 'FILTER NOT SET'),
            self::FILTER_MORE => Yii::t('frontend', 'FILTER MORE'),
            self::FILTER_MORE_EQUAL => Yii::t('frontend', 'FILTER MORE EQUAL'),
            self::FILTER_LESS => Yii::t('frontend', 'FILTER LESS'),
            self::FILTER_LESS_EQUAL => Yii::t('frontend', 'FILTER LESS EQUAL'),
            self::FILTER_EQUAL => Yii::t('frontend', 'FILTER EQUAL'),
            self::FILTER_NOT_EQUAL => Yii::t('frontend', 'FILTER NOT EQUAL'),
            self::FILTER_BETWEEN => Yii::t('frontend', 'FILTER BETWEEN'),
            self::FILTER_NOT_BETWEEN => Yii::t('frontend', 'FILTER NOT BETWEEN')
        ];
    }

    /**
     * Gets array of accessible filters for all columns
     * 
     * @return array
     */
    public static function getAccessibleFiltersByColumns()
    {
        
        return [
            'id' => self::getNumericFilters(),
            'type_packet' => self::getCommonFilters(),
            'date' => self::getDateFilters(),
            'imei' => self::getCommonFilters(),
            'address' => self::getCommonFilters()
        ];
    }

    /**
     * Gets accessible filters by column
     * 
     * @return array
     */
    public static function getAccessibleFiltersByColumnName($name)
    {
        
        return self::getAccessibleFiltersByColumns()[$name];
    }

    /**
     * Applies FILTER_NOT_SET filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByNotSetFilter($query)
    {
        
        return $query;
    }

    /**
     * Applies FILTER_CELL_EMPTY filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByCellEmptyFilter($query, $columnName)
    {
        $query = $query->andWhere(['=', "LENGTH($columnName)", 0]);
        
        return $query;
    }

    /**
     * Applies FILTER_CELL_NOT_EMPTY filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByCellNotEmptyFilter($query, $columnName)
    {
        $query = $query->andWhere(['>', "LENGTH($columnName)", 0]);
        
        return $query;
    }

    /**
     * Applies FILTER_TEXT_CONTAIN filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByTextContain($query, $columnName, $params)
    {
        if ($columnName == 'type_packet') {
            $typeIds = Jlog::getTypePacketsFromNameByContainCondition($params['val1'][$columnName]);
            
            return $query->andWhere(['type_packet' => $typeIds]);
        }
        
        $query = $query->andWhere([ 'like', $columnName, $params['val1'][$columnName] ]);
        
        return $query;   
    }

    /**
     * Applies FILTER_TEXT_NOT_CONTAIN filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByTextNotContain($query, $columnName, $params)
    {
        if ($columnName == 'type_packet') {
            $typeIds = Jlog::getTypePacketsFromNameByNotContainCondition($params['val1'][$columnName]);
            
            return $query->andWhere(['type_packet' => $typeIds]);
        }
        
        $query = $query->andWhere([ 'not like', $columnName, $params['val1'][$columnName] ]);

        return $query;
    }

    /**
     * Applies FILTER_TEXT_START_FROM filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByTextStartFrom($query, $columnName, $params)
    {
        if ($columnName == 'type_packet') {
            $typeIds = Jlog::getTypePacketsFromNameByStartCondition($params['val1'][$columnName]);
            
            return $query->andWhere(['type_packet' => $typeIds]);
        }
        
        $val1 = $params['val1'][$columnName];
        $query = $query->andWhere(["LOCATE('{$val1}', {$columnName})" => 1]);
        
        return $query;
    }

    /**
     * Applies FILTER_TEXT_END_WITH filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByTextEndWith($query, $columnName, $params)
    {
        if ($columnName == 'type_packet') {
            $typeIds = Jlog::getTypePacketsFromNameByEndCondition($params['val1'][$columnName]);
            
            return $query->andWhere(['type_packet' => $typeIds]);
        }
        
        $val1 = $params['val1'][$columnName];
        $val1Length = mb_strlen($val1);
        $query = $query->andWhere(
            ["LOCATE('{$val1}', SUBSTRING({$columnName}, CHAR_LENGTH({$columnName}) -{$val1Length} + 1 ))" => 1]
        );
        
        
        return $query;
    }

    /**
     * Applies FILTER_TEXT_EQUAL filter
     * 
     * @param ActiveQuery $query
     * @return array
     */
    private function changeQueryByTextEqual($query, $columnName, $params)
    {
        if ($columnName == 'type_packet') {
            $typeId = Jlog::getTypePacketFromName($params['val1'][$columnName]);
            
            return $query->andWhere(['type_packet' => $typeId]);
        }
        
        $query = $query->andWhere([$columnName => $params['val1'][$columnName]]);

        return $query;
    }

    /**
     * Gets timestamp intervals, specified by dateParam
     * 
     * @param string $dateParam
     * @param string $columnName
     * @param array $params
     * @return array
     */
    private function getTimestampIntervals($dateParam, $columnName, $params)
    {
        switch($dateParam) {
            case 'today':
                
                return [
                    'min' => strtotime("today midnight"),
                    'max'=> strtotime("now")
                ];
            case 'tomorrow':
                
                return [
                    'min' => strtotime("tomorrow midnight"),
                    'max' => strtotime("tomorrow midnight + 1 days")
                ];
            case 'yesterday':
                
                return [
                    'min' => strtotime('yesterday midnight'),
                    'max' => strtotime('today midnight')
                ];
            case 'lastweek':
                if (date("D") == "Mon") {
                    $minExpr = "last monday";
                    $maxExpr = "monday";
                } else {
                    $minExpr = "last monday -7 days";
                    $maxExpr = "last monday";
                }
                
                return [
                    'min' => strtotime($minExpr),
                    'max' => strtotime($maxExpr)
                ];
            case "lastmonth":
                $month = date('m', strtotime("last month"));
                $year = date('Y', strtotime("last month"));
                $dateStart = $year."-".$month."-01 00:00:00";
                
                $currentMonth = date('m', strtotime("now"));
                $currentYear = date('Y', strtotime("now"));
                $dateEnd = $currentYear."-".$currentMonth."-01 00:00:00";
                
                return [
                    'min' => strtotime($dateStart),
                    'max' => strtotime($dateEnd)
                ];
            case "lastyear":
                $lastYear = date("Y", strtotime("last year"));
                $currentYear = date("Y", strtotime("now"));
                $dateStart = $lastYear."-01-01 00:00:00";
                $dateEnd = $currentYear."-01-01 00:00:00";
                
                return [
                   'min' => strtotime($dateStart),
                   'max' => strtotime($dateEnd) 
                ];
            case "certain":
                $dateStart = date("Y-m-d", strtotime($params['val2'][$columnName]));
                $dateEnd = date("Y-m-d", strtotime("+1 days", strtotime($dateStart)));
                
                return [
                   'min' => strtotime($dateStart),
                   'max' => strtotime($dateEnd) 
                ];
        }
    }

    /**
     * Applies common filters
     * 
     * @param ActiveQuery $query
     * @param string $columnName
     * @param array $params
     * @return array
     */
    public function changeQueryByCommonFilter($query, $columnName, $params)
    {
        switch($params['filterCondition'][$columnName]) {
            case self::FILTER_NOT_SET:
                
                break;
            case self::FILTER_CELL_EMPTY:
                $query = $this->changeQueryByCellEmptyFilter($query, $columnName);

                break;
            case self::FILTER_CELL_NOT_EMPTY:
                $query = $this->changeQueryByCellNotEmptyFilter($query, $columnName);
                
                break;
            case self::FILTER_TEXT_CONTAIN:
                $query = $this->changeQueryByTextContain($query, $columnName, $params);
                
                break;
            case self::FILTER_TEXT_NOT_CONTAIN:
                $query = $this->changeQueryByTextNotContain($query, $columnName, $params);
                
                break;
            case self::FILTER_TEXT_START_FROM:
                $query = $this->changeQueryByTextStartFrom($query, $columnName, $params);
                
                break;
            case self::FILTER_TEXT_END_WITH:
                $query = $this->changeQueryByTextEndWith($query, $columnName, $params);
                
                break;
            case self::FILTER_TEXT_EQUAL:
                $query = $this->changeQueryByTextEqual($query, $columnName, $params);
                
                break;
        }

        return $query;
    }

    /**
     * Applies date filters
     * 
     * @param ActiveQuery $query
     * @param string $columnName
     * @param array $params
     * @return array
     */
    public function changeQueryByDateFilter($query, $columnName, $params)
    {
        $timeIntervals = $this->getTimestampIntervals(
            $params['val1'][$columnName], $columnName, $params 
        );
        $min = $timeIntervals['min'];
        $max = $timeIntervals['max'];
        switch($params['filterCondition'][$columnName]) {
            case self::FILTER_DATE :
                $query = $query->andWhere(
                    [">=", "UNIX_TIMESTAMP(STR_TO_DATE({$columnName}, '".Imei::MYSQL_DATE_TIME_FORMAT."'))", $min]
                );
                $query = $query->andWhere(
                    ["<", "UNIX_TIMESTAMP(STR_TO_DATE({$columnName}, '".Imei::MYSQL_DATE_TIME_FORMAT."'))", $max]
                );
                
                break;
            case self::FILTER_DATE_BEFORE:
                $query = $query->andWhere(
                    ["<", "UNIX_TIMESTAMP(STR_TO_DATE({$columnName}, '".Imei::MYSQL_DATE_TIME_FORMAT."'))", $min]
                );
                
                break;
            case self::FILTER_DATE_AFTER:
                $query = $query->andWhere(
                    [">=", "UNIX_TIMESTAMP(STR_TO_DATE({$columnName}, '".Imei::MYSQL_DATE_TIME_FORMAT."'))", $max]
                );
                
                break;
        }
        
        return $query;
    }

    /**
     * Applies numeric filters
     * 
     * @param ActiveQuery $query
     * @param string $columnName
     * @param array $params
     * @return array
     */
    public function changeQueryByNumericFilter($query, $columnName, $params)
    {
        $min = (
            $params['val1'][$columnName] < $params['val2'][$columnName] ?
            $params['val1'][$columnName] : $params['val2'][$columnName]
        );
        $max = (
            $params['val1'][$columnName] >= $params['val2'][$columnName] ?
            $params['val1'][$columnName] : $params['val2'][$columnName]
        );
        switch($params['filterCondition'][$columnName])
        {
            case self::FILTER_MORE:
                $query = $query->andWhere([">", $columnName, $params['val1'][$columnName]]);
                
                break;
            case self::FILTER_MORE_EQUAL:
                $query = $query->andWhere([">=", $columnName, $params['val1'][$columnName]]);
                
                break;
            case self::FILTER_LESS:
                $query = $query->andWhere(["<", $columnName, $params['val1'][$columnName]]);
                
                break;
            case self::FILTER_LESS_EQUAL:
                $query = $query->andWhere(["<=", $columnName, $params['val1'][$columnName]]);
                
                break;
            case self::FILTER_EQUAL:
                $query = $query->andWhere(["=", $columnName, $params['val1'][$columnName]]);

                break;
            case self::FILTER_NOT_EQUAL:
                $query = $query->andWhere(["!=", $columnName, $params['val1'][$columnName]]);

                break;    
            case self::FILTER_BETWEEN:
                $condition = new \yii\db\conditions\BetweenCondition(
                    $columnName, 'BETWEEN', $min, $max
                );
                $query = $query->andWhere($condition);
                
                break;
            case self::FILTER_NOT_BETWEEN:
                $condition = new \yii\db\conditions\BetweenCondition(
                    $columnName, 'NOT BETWEEN', $min, $max
                );
                $query = $query->andWhere($condition);
                
                break;
        }

        return $query;
    }

    /**
     * Applies conditional filter related to filter category
     * 
     * @param ActiveQuery $query
     * @param string $columnName
     * @param array $params
     * @param integer $filterCategory
     * @return array
     */
    public function applyFilterByConditionMethod($query, $columnName, $params, $filterCategory)
    {
        if (empty($params['filterCondition'][$columnName])) {

            return $query;
        }
        
        switch($filterCategory) {
            case self::FILTER_CATEGORY_COMMON :
                $query = $this->changeQueryByCommonFilter($query, $columnName, $params);
                
                break;
            case self::FILTER_CATEGORY_DATE:
                $this->changeQueryByDateFilter($query, $columnName, $params);
                
                break;
            case self::FILTER_CATEGORY_NUMERIC:
                $this->changeQueryByNumericFilter($query, $columnName, $params);
                
                break;
        }

        return $query;
    }

    /**
     * Applies value filter
     * 
     * @param ActiveQuery $query
     * @param string $columnName
     * @param array $params
     * @return array
     */
    public function applyFilterByValueMethod($query, $columnName, $params)
    {
        if (empty($params['inputValue'][$columnName])) {

            return $query;
        }

        if ($columnName == 'type_packet') {
            $typeIds = Jlog::getTypePacketsFromNameByContainCondition($params['inputValue'][$columnName]);
            
            return $query->andWhere(['type_packet' => $typeIds]);
        }

        return $query->andWhere(['like', $columnName, $params['inputValue'][$columnName]]);
    }

    /**
     * Gets all distinct imeis from j_log, mapped to array of objects
     * 
     * @return array
     */
    public function getImeisMapped()
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new Jlog());
        $imeis = $query->select('imei')->distinct()->all();
        $imeisMapped = [];
        $counter = 1;
        foreach($imeis as $imei) {
            $imeisMapped[] = (object)['id' => $counter++, 'value' => $imei->imei]; 
        }

        return $imeisMapped;
    }

    /**
     * Gets all distinct addresses from j_log, mapped to array of objects
     * 
     * @return array
     */
    public function getAddressesMapped()
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new Jlog());
        $addresses = $query->select('address')->distinct()->all();
        $addressesMapped = [];
        $counter = 1;
        foreach($addresses as $address) {
            $addressesMapped[] = (object)['id' => $counter++, 'value' => $address->address]; 
        }

        return $addressesMapped;
    }

}
