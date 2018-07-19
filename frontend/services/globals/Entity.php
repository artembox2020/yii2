<?php

namespace frontend\services\globals;

use common\models\User;
use Yii;
use yii\di\Instance;

/**
 * Class Entity
 * @package frontend\services\globals
 */
class Entity implements EntityInterface
{
    /**
     * @param null $id
     * @param null $instance
     * @return null|Instance
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUnitPertainCompany($id, $instance)
    {
        $unit = $instance::findOne(['id' => $id, 'company_id' => $this->getCompanyId()]);
        $this->checkAccess($unit);

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
     * @param $unit
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function checkAccess($unit)
    {
        if (!$unit) {
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
}
