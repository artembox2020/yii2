<?php

namespace api\modules\v2d00\UseCase\StatePackage;

use api\modules\v2d00\UseCase\ImeiInit\ImeiInit;
use Exception;
use frontend\models\Imei;
use Yii;

class StatePackage
{
    public function create($items)
    {
        try {
            $this->addImei($items);
            $this->addWm($items);
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 500;
            return $exception;
        }

        Yii::$app->response->statusCode = 201;
        return 'SP create!';
    }

    public function addImei($items)
    {
        $imei = ImeiInit::getImei($items->imei);
        return 'Ok';
    }

    public function addWm($items)
    {
        $imei = ImeiInit::getImei($items->imei);
        return 'Ok';
    }
}
