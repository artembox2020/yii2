<?php

namespace frontend\services\globals;

/**
 * Interface EntityInterface
 * @package frontend\services\globals
 */
interface EntityInterface
{
    /**
     * @param null $id
     * @param null $instance
     * @return null|Instance
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUnitPertainCompany($id, $instance);

    /**
     * @param null $instance
     * @return null|array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUnitsPertainCompany($instance);
}
