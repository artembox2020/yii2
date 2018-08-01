<?php

namespace frontend\services\globals;

use common\models\User;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use \yii\jui\AutoComplete;
use yii\web\JsExpression;

/**
 * Class EntityHelper
 * @package frontend\services\globals
 */
class EntityHelper implements EntityHelperInterface
{
    /**
     * @param Instance $instance
     * @param int $status
     * @return array
     */
    public function tryFilteredStatusDataEx($instance, $status)
    {
        $entity = new Entity();
        $units = $instance::find()
            ->andWhere(['company_id' => $entity->getCompanyId(), 'status' => $status])
            ->all();
        if (!$units) {
            $units = [];
        }

        return $units;   
    }
    
    /**
     * @param Instance $instance
     * @param int $status
     * @param array $params
     * @return array
     * @throws \yii\web\HttpException
     */
    public function tryFilteredStatusDataMapped($instance, $status, Array $params, Array $unitIds = [])
    {
        $units = $this->tryFilteredStatusDataEx($instance, $status);

        // addition of some more units if specified
        if (!empty($unitIds)) {
            $entity = new Entity();
            $ids = ArrayHelper::getColumn($units, 'id');
            $unitIds = array_diff($unitIds, $ids);
            $additionalUnits = $entity->tryUnitsPertainCompanyByIds($unitIds, $instance);
            if ($additionalUnits) {
                $units = array_merge($units, $additionalUnits);
            }
        }

        $maps = [];
        $array_keys = array_keys($params);
        $key = !empty($array_keys[0]) ? $array_keys[0] : false;
        if (!$key) {

            return $maps;
        }
        foreach ($units as $unit) {
            $value = $this->tryUnitRelationData($unit, $params);
            $id = $this->tryUnitRelation($unit, $key);
            if ($id && $value) {
                $maps[] = (object)['id' => $id, 'value' => $value];
            }
        }

        return $maps;
    }

    /**
     * @param array $params
     * @return \yii\jui\AutoComplete
     * @throws \yii\web\HttpException
     */
    public function AutoCompleteWidgetFilteredData(Array $params)
    {
        extract($params);
        $queryString = '';

        // exclude params ['id', 'foreignId', '_pjax'] from query string
        if (!empty(Yii::$app->request->queryParams)) {
            $excludeParams = ['id' => 1, 'foreignId' => 1, '_pjax' => 1];
            $queryParams = array_diff_key(Yii::$app->request->queryParams, $excludeParams);
            $queryString = '&'.http_build_query($queryParams);
        }

        $selectExpr = new JsExpression(
            "function( event, ui )
            {
                if (typeof ui.item != 'undefined' && ui.item != null)
                {
                    location.href = 
                        '{$url}'+'?id='+{$model->id}+
                        '&foreignId='+ui.item.id+'{$queryString}';
                }
			}"
		);

        return AutoComplete::widget([
            'name' => $name,

            'options' => $options,

            'clientOptions' => [
                'source' => $source,
                'autoFill' => true,
                'select' => $selectExpr,
            ],
        ]);    
    }

    /**
     * @param Instance $unit
     * @param string $relation
     * @return Instance|bool
     */
    public function tryUnitRelation($unit, $relation)
    {
        if (!$unit) {
            
            return false;
        }

        if (empty($relation) || empty($unit->$relation)) {

            return false;
        } else {

            return $unit->$relation;
        }
    }

    /**
     * @param Instance $unit
     * @param array $params
     * @return string|bool
     * @throws \yii\web\HttpException
     */
    public function tryUnitRelationData($unit, $params)
    {
        try {
            $relation = array_keys($params)[0];
            $names = $params[$relation];
            $glue = empty($params[0]) ? ' ' : $params[0];

            if (!$relationObject = $this->tryUnitRelation($unit, $relation)) {

                return false;
            }

            if (!is_object($relationObject)) {
                $relationObject = $unit;
            }

            if (is_array($names)) {
                $value = '';
                foreach($names as $name) {
                    $value.= $relationObject->$name.$glue;
                }
                if (!empty($value)) {
                    $value = mb_substr($value, 0, -mb_strlen($glue));
                }

            } else {
                $value = $relationObject->$names;
            }
        }
        catch (Exception $e) {
            throw new \yii\web\HttpException(
                500, 
                Yii::t('common', 'Invalid Array [Key => Value] Configuration')
            );
        }

        return $value;    
    }
}
