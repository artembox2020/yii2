<?php

namespace api\modules\v2d00\UseCase\Log;

use Yii;

class Log
{
    const CENTRAL_BOARD = '-1';
    const WASH_MACHINE = '0';

    public function create($items)
    {
        if ($items->pac->devType == self::CENTRAL_BOARD) {
            try {
                $create = new CentralBoardLog();
                $create->add($items);
                Yii::$app->response->statusCode = 201;
                return 'CB';
            } catch (\Exception $exception) {
                return $exception;
            }
        }

        if ($items->pac->devType == self::WASH_MACHINE) {
            $create = new WasheMachineLog($items);
            Yii::$app->response->statusCode = 201;
            return 'WM';
        }
    }
}
