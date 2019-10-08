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
            $result = $service->getStaff($items->address, $items->wm);
        } catch (Exception $e) {
            return $e->getMessage();
        }



        $returnData = [
            'chat_id' => 'xxx',
//            'num_w' => $result['num_w'],
//            'status_w' => $result['status_w'],
//            'time' => $result['time'],
//            'message' => $result['message'],
//            'key' => $result['key']
        ];
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $returnData;

        return $returnData;

        // Insert create monitoring
        Debugger::dd($result);
    }
}
