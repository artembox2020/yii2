<?php

namespace api\modules\v2d00\controllers;

use api\modules\v2d00\UseCase\Encashment\Encashment;
use api\modules\v2d00\UseCase\ImeiInit\ImeiInit;
use api\modules\v2d00\UseCase\Log\Log;
use api\modules\v2d00\UseCase\StatePackage\StatePackage;
use frontend\controllers\Controller;
use frontend\services\custom\Debugger;
use Yii;
use yii\web\Response;

/**
 * Class JsonController
 * @package api\modules\v2d00\controllers
 */
class JsonController extends Controller
{
    const LOG = 'L';
    const INI = 'I';
    const STATUS = 'S';
    const ENCASHMENT = 'C';

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [],
                'actions' => [
                    'incoming' => [
                        'Origin' => ['*'],
                        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'HEAD', 'OPTIONS'],
                        'Access-Control-Request-Headers' => ['*'],
                        'Access-Control-Allow-Credentials' => null,
                        'Access-Control-Max-Age' => 86400,
                        'Access-Control-Expose-Headers' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['index'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = json_decode(file_get_contents("php://input"));

        // сотояние
        if ($items->type == self::LOG) {
            $log = new Log();

            return $log->create($items);
        }

        // инициализация
        if ($items->type == self::INI) {
            $init = new ImeiInit();
//            $initLog = new InitLog();
//            $initLog->create($items);
            return $init->add($items);
        }

        if ($items->type == self::STATUS) {
            $status = new StatePackage();

            return $status->create($items);
        }

        // инкассация
        if ($items->type == self::ENCASHMENT) {
            $encashment = new Encashment();
            return $encashment->add($items);
        }
        return Yii::$app->response->statusCode = 400;
    }
}
