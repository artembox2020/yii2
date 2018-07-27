<?php

namespace frontend\services\globals;

use common\models\User;
use frontend\services\custom\Debugger;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use \yii\jui\AutoComplete;
use yii\web\JsExpression;

/**
 * Class Entity
 * @package frontend\services\globals
 */
class Entity implements EntityInterface
{
    /** @var int  */
    const ONE = 1;

    /**
     * @param null $id
     * @param null $instance
     * @param $returnIfZero
     * @return null|Instance
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUnitPertainCompany($id, $instance, $returnIfZero = -1)
    {
        $unit = $instance::findOne(['id' => $id, 'company_id' => $this->getCompanyId()]);
        $unit = $this->checkAccess($unit, $returnIfZero);

        return $unit;
    }

    /**
     * @param null $instance
     * @return null|array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUnitsPertainCompany($instance)
    {
        $units = $this->getUnitsQueryPertainCompany($instance)->all();
        $this->checkAccess($units);

        return $units;
    }
    
    /**
     * @param null $instance
     * @return yii\db\Query
     */
    public function getUnitsQueryPertainCompany($instance)
    {
        $units = $instance::find()->andWhere(['company_id' => $this->getCompanyId()]);
        
        return $units;
    }
    
    /**
     * Extended version of getFilteredStatusData
     * 
     * @param $instance
     * @param $status
     * @return array
     */
    public function getFilteredStatusDataEx($instance, $status)
    {
        $units = $instance::find()
            ->andWhere(['company_id' => $this->getCompanyId(), 'status' => $status])
            ->all();
        if (!$units) {
            $units = [];
        }

        return $units;
    }
    
    /**
     * @param $instance
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function getFilteredStatusData($instance)
    {
        $units = $instance::find(['company_id' => $this->getCompanyId()])
            ->where(['status' => self::ONE])
            ->all();
        $this->checkAccess($units);

        return $units;
    }

    /**
     * @param $unit
     * @param mixed $returnOnFail
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function checkAccess($unit, $returnOnFail = -1)
    {
        if (!$unit) {
            if ($returnOnFail !== -1) {
                
                return $returnOnFail;
            }
            
            throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
        }

        return $unit;
    }

    /**
     * @return int
     */
    public function getCompanyId()
    {
        $user = User::findOne(Yii::$app->user->id);

        return $user->company_id;
    }
    
    /**
     * Gets and maps filtered status data, specified by $map parameter
     * 
     * @param $instance
     * @param $status
     * @param $map
     * @return array
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getFilteredStatusDataMapped($instance, $status, Array $map, $unitId = false)
    {
        $units = $this->getFilteredStatusDataEx($instance, $status);
        
        // addition one more unit if specified
        if (!empty($unitId)) {
            $unitIds = ArrayHelper::getColumn($units, 'id');
            if (!in_array($unitId, $unitIds)) {
                $unit = $this->getUnitPertainCompany($unitId, $instance, false);
                if ($unit) {
                    $units = array_merge($units, [$unit]);
                }
            }
        }
        
        try {
            $key = array_keys($map)[0];
            $names = $map[$key];
            $glue = empty($map[0]) ? ' ' : $map[0];
            $maps = [];
            foreach($units as $unit) {
                $value = '';
                if (is_array($names))
                {
                    foreach($names as $name) {
                        $value.= $unit->$name.$glue;
                    }
                    if (!empty($value)) {
                        $value = mb_substr($value, 0, -mb_strlen($glue));
                    }
                }
                else {
                    $value = $unit->$names;
                }
                $maps[] = (object)['id' => $unit->$key, 'value' => $value];
            }
        }
        catch(Exception $e) {
            
            throw new \yii\web\HttpException(
                500, 
                Yii::t('common', 'Invalid Array [Key => Value] Configuration')
            );
        }
        
        return $maps;
    }
    
    /**
     * @param $params
     * @return \yii\jui\AutoComplete
     */
    public function AutoCompleteWidgetFilteredData($params)
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
     * @param $unit
     * @param $params
     * @param $returnIfNotExist
     * @return string
     * @throw \yii\web\NotFoundHttpEception
     * @thorw \yii\web\ServerErrorHttpException
     */
    public function getUnitRelationData($unit, $params, $returnIfNotExist = -1)
    {
        if (!$unit) {
            throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
        }
        try {
            $relation = array_keys($params)[0];
            $names = $params[$relation];
            $glue = empty($params[0]) ? ' ' : $params[0];

            if (empty($unit->$relation)) {
                if ($returnIfNotExist == -1) {
                    throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
                }
                else {
                    
                    return $returnIfNotExist;
                }
            }
            else {
                if (is_array($names)) {
                    $value = '';
                    foreach($names as $name) {
                        $value.= $unit->$relation->$name.$glue;
                    }
                    if (!empty($value)) {
                        $value = mb_substr($value, 0, -mb_strlen($glue));
                    }

                }
                else {
                    $value = $unit->$relation->$names;
                }
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
