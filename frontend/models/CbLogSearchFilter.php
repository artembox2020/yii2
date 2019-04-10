<?php

namespace frontend\models;

use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CbLogSearchFilter
 * @package frontend\models
 */
class CbLogSearchFilter extends JlogSearch
{
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

        $entity = new Entity();

        if (in_array($columnName, ['address','imei'])) {
            $unitIds = $this->getIdsByColumnName($columnName, $params['inputValue'][$columnName], self::FILTER_TEXT_CONTAIN);

            return $query->andWhere([$columnName.'_id' => $unitIds]);
        } elseif ($columnName == 'date') {
            $timestampStart = strtotime($params['inputValue'][$columnName]);

            return $query->andWhere(['>=', 'unix_time_offset', $timestampStart])->andWhere(['<', 'unix_time_offset', $timestampStart + 3600*24]);
        }

        return $query->andWhere(['like', $columnName, $params['inputValue'][$columnName]]);
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

        if ($columnName == 'address') {
            $addressIds = $this->getIdsByColumnName($columnName, $params['val1'][$columnName], $params['filterCondition'][$columnName]);

            return $query->andWhere([$columnName.'_id' => $addressIds]);
        }

        if ($columnName == 'date') {
            $columnName = 'unix_time_offset';
            $params['val1'][$columnName] = $params['val1']['date'];
            $params['val2'][$columnName] = $params['val2']['date'];
            $params['filterCondition'][$columnName] = $params['filterCondition']['date'];
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
     * Gets ids by column name and common filter condition
     *
     * @param string $columnName
     * @param string $value
     * @param int $filterCondition
     * @return array
     */
    public function getIdsByColumnName($columnName, $value, $filterCondition)
    {
        $entity = new Entity();
        $expression = $columnName;
        $operator = 'like';

        switch ($filterCondition) {
            case self::FILTER_NOT_SET:
                
                break;
            case self::FILTER_CELL_EMPTY:
                $operator = 'is';
                $value = new \yii\db\Expression('null');
                list($columnName, $expression, $operator, $value)
                = 
                $this->setColumnExpressionOperatorValue(
                    $columnName, $expression, $operator, $value
                );
                break;
            case self::FILTER_CELL_NOT_EMPTY:
                $operator = 'is not';
                $value = new \yii\db\Expression('null');
                list($columnName, $expression, $operator, $value)
                = 
                $this->setColumnExpressionOperatorValue(
                    $columnName, $expression, $operator, $value
                );
                break;    
            case self::FILTER_TEXT_CONTAIN:

                break;
            case self::FILTER_TEXT_NOT_CONTAIN:
                $operator = 'not like';
                
                break;    
            case self::FILTER_TEXT_START_FROM:
                
                $value = $value.'%';
                break;
            case self::FILTER_TEXT_END_WITH:
                $value = '%'.$value;
                break;
            case self::FILTER_TEXT_EQUAL:
                $operator = '=';
                break;
        }

        if (
            $operator == 'like' &&
            in_array($filterCondition, [self::FILTER_TEXT_START_FROM, self::FILTER_TEXT_END_WITH])
        ) {
            $whereCondition = [$operator, $expression, explode(",", $value)[0], false];
        }
        else {
            $whereCondition = [$operator, $expression, explode(",", $value)[0]];
        }

        $unitModels = ['address' => new AddressBalanceHolder(), 'imei' => new Imei()];
        $unitsQuery =  $unitModels[explode('_', $columnName)[0]]::find()->where($whereCondition)
                                                  ->andWhere(['company_id' => $entity->getCompanyId()]);

        if ($columnName == 'address' && count(explode(",", $value)) > 1) {
            $floor = trim(explode(",", $value)[1]);
            $unitsQuery = $unitsQuery->andWhere(['like', 'floor', $floor]);
        }

        $units = $unitsQuery->all();

        if (empty($units)) {

            return [];
        }

        $unitIds = \yii\helpers\ArrayHelper::getColumn($units, 'id');

        return $unitIds;
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
                $query = $query->andWhere(['>=', $columnName, $min])->andWhere(['<=', $columnName, $max]);

                break;
            case self::FILTER_DATE_BEFORE:
                $query = $query->andWhere(['<', $columnName, $min]);

                break;
            case self::FILTER_DATE_AFTER:
                $query = $query->andWhere(['>=', $columnName, $max]);

                break;
            case self::FILTER_DATE_FROM:
                $bhSummarySearch = new BalanceHolderSummarySearch();
                $start = $bhSummarySearch->getDayBeginningTimestampByTimestamp($min);
                $query = $query->andWhere(['>=', $columnName, $start]);

                break;
        }

        return $query;
    }

    /**
     * Sets 'column', 'expression', 'operator', 'value' variables
     * 
     * @param string $columnName
     * @param string $expression
     * @param string $operator
     * @param string $value
     * @return array
     */
    private function setColumnExpressionOperatorValue($columnName, $expression, $operator, $value)
    {
        if (!in_array($columnName, ['address', 'imei'])) {

            return [$columnName, $expression, $operator, $value];
        }

        $columnName = $columnName.'_id';
        $expression = 'id';
        $value = '0';
        $operator = $operator === 'is' ? '=' : '!=';

        return [$columnName, $expression, $operator, $value];
    }
}
