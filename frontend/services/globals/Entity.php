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
     * @return null|Instance
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUnitPertainCompany($id, $instance)
    {
        if (!$unit = $this->tryUnitPertainCompany($id, $instance)) {
         
            throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
        }
        
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
     * @param bool $raiseException
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function checkAccess($unit, $raiseException = true)
    {
        if (!$unit) {
            if ($raiseException) {
                
                throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
            } else {
                
                return false;
            }
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
     * Attempts to get unit pertaining to company
     * In case it is not found returns bool(false)
     * 
     * @param null $id
     * @param null $instance
     * @return bool|Instance
     */
    public function tryUnitPertainCompany($id, $instance)
    {
        $unit = $instance::findOne(['id' => $id, 'company_id' => $this->getCompanyId()]);

        return $this->checkAccess($unit, false);  
    }
    
    /**
     * Attempts to get units by its ids
     * In case units not found returns bool(false)
     * 
     * @param array $unitIds
     * @param null $instance
     * @return bool|array
     */
    public function tryUnitsPertainCompanyByIds(Array $unitIds, $instance)
    {
        $units = $instance::find()
            ->andWhere(
                [
                    'company_id' => $this->getCompanyId(),
                    'id' => $unitIds
                ])
            ->all();

        return $this->checkAccess($units, false);
    }
}
