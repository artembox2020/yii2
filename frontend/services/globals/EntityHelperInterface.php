<?php

namespace frontend\services\globals;

use yii\di\Instance;

/**
 * Interface EntityHelperInterface
 * @package frontend\services\globals
 */
interface EntityHelperInterface
{
    /**
     * Attempts to get array of objects, filtered by status value
     * If not found, empty array is to be returned
     * @param Instance $instance
     * @param int $status
     * @return array
     */
    public function tryFilteredStatusDataEx($instance, $status);

    /**
     * Gets and maps filtered status data, specified by $params parameter
     * 
     * @param Instance $instance
     * @param int $status
     * @param array $params
     * @return array
     * @throws \yii\web\HttpException
     */
    public function tryFilteredStatusDataMapped($instance, $status, Array $params, Array $unitIds = []);

    /**
     * @param array $params
     * @return \yii\jui\AutoComplete
     * @throws \yii\web\HttpException
     */
    public function AutoCompleteWidgetFilteredData(Array $params);

    /**
     * Attempts to get relation of the instance
     * In case of not existing returns bool(false)
     * 
     * @param Instance $unit
     * @param string $relation
     * @return Instance|bool
     */
    public function tryUnitRelation($unit, $relation);

    /**
     * Attempts to retrieve relations data, specified by $params
     * In case of not existing returns bool(false)
     * 
     * @param Instance $unit
     * @param array $params
     * @return string|bool
     * @throws \yii\web\HttpException
     */
    public function tryUnitRelationData($unit, $params);
}
