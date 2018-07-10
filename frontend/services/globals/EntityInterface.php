<?php

namespace frontend\services\globals;

use yii\di\Instance;

/**
 * Interface EntityInterface
 * @package frontend\services\globals
 */
interface EntityInterface
{
    /**
     * @param $id
     * @param $instance
     * @return null|Instance
     */
    public function getUnitPertainCompany($id, $instance);

    /**
     * @param $instance
     * @return null|array
     */
    public function getUnitsPertainCompany($instance);
}
