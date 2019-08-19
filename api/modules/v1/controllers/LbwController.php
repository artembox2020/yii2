<?php

namespace api\modules\v1\controllers;

use frontend\controllers\Controller;
use frontend\services\custom\Debugger;
use Yii;

/**
 * Class LbwController
 * @package api\modules\v1\controllers
 */
class LbwController extends Controller
{
    const CENTRAL_BOARD = '-1';
    const WASH_MACHINE = '0';

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [],
                'actions' => [
                    'incoming' => [
                        'Origin' => ['*'],
                        'Access-Control-Request-Method' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $items = json_decode(file_get_contents("php://input"));

        if ($items->devType == self::CENTRAL_BOARD) {
            Yii::$app->response->statusCode = 201;

            Debugger::dd($items);

            return 'CB';
        }

        if ($items->devType == self::WASH_MACHINE) {
            Yii::$app->response->statusCode = 201;
            return 'WM';
        }

        return Yii::$app->response->statusCode = 400;
    }
}
