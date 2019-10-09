<?php

namespace api\modules\t1\controllers;

use api\modules\t1\UseCase\Monitoring;
use frontend\controllers\Controller;
use frontend\services\custom\Debugger;
use Yii;
use yii\db\Exception;
use yii\web\Response;

/**
 * Class MonitoringController
 * @package api\modules\t1\controllers
 */
class MonitoringController extends Controller
{
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

        $service = new Monitoring();
        try {
            $result = $service->getStaff($items);
        } catch (Exception $e) {
            return $e->getMessage();
        }

//        Debugger::dd($result);
//
//        $returnData = [
//            [
//                'chat_id' => '111111',
//                'num_w' => 1,
//                'status_w' => 2,
//                'time' => 10,
//                'key' => 'anfu4h3uh34uf3gf'
//            ],
//            [
//            'chat_id' => '2222222',
//            'num_w' => 1,
//            'status_w' => 2,
//            'time' => 10,
//            'key' => 'anfu4h3uh34uf3gf'
//        ]
//        ];
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $result;

        return $this->asJson($result);
    }
}
