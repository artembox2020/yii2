<?php

namespace api\modules\v2d00\UseCase\Encashment;

use api\modules\v2d00\UseCase\ImeiInit\ImeiInit;
use Yii;

/**
 * Class Encashment
 * @package api\modules\v2d00\UseCase\Encashment
 */
class Encashment
{
    /**
     * @param $items
     * @return \Exception|string
     */
    public function add($items)
    {
        try {
            $imei = ImeiInit::getImei($items->imei);
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 500;
            return $exception;
        }

        Yii::$app->response->statusCode = 201;
        return 'Encashment complete!';
    }
}
