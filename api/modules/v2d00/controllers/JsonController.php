<?php

namespace api\modules\v2d00\controllers;

use api\modules\v2d00\UseCase\Log\Log;
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
    const LOG = '2.00 L';

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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = json_decode(file_get_contents("php://input"));

        if ($items->type == self::LOG) {
            $log = new Log();
            return $log->create($items);
        }

        return Yii::$app->response->statusCode = 400;
    }
}
