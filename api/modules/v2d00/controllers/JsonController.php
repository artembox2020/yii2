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
//        Debugger::dd($items->type);
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
    
    public function actionTestCbLog()
    {
        $money = [
            "total" => "116",
            "totalCards" => "0",
            "collection" => "116",
            "numberNotes" => "23",
            "numberCoins" => "0"
        ];

        $washAddition = [
            "prewash" => "0",
            "rising_plus" => "0",
            "intensive_washing" => "0",
        ];

        $counters = [
            "water" => "0",
            "power" => "0"
        ];

        $event = [
            "num" => "0",
            "cenBoard" => "0",
            "washer" => "0",
            "dryer" => "0",
            "cleaner" => "0",
            "unitCards" => "0"
        ];

        $pac = [
            "id" => "0",
            "prio" => "0",
            "utc" => "1565299425",
            "devType" => "-1",
            "numberDev" => "0",
            "event" => $event,
            "devCash" => "0",
            "washAddition" => $washAddition,
            "money" => $money,
            "counters" => $counters,
            "priceMode" => "0",
            "tariff" => "0",
            "washMode" => "0",
            "washTemp" => "0",
            "washExtrac" => "0",
            "rssi" => "-70"
        ];

        $data_string = json_encode([
            "imei" => "862643034067094",
            "type" => self::LOG,
            "time" => "1566199266",
            "pac" => $pac
        ]);

        $url = Yii::$app->homeUrl.\yii\helpers\Url::to(['/v2d00/json/index']);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json'
        ));

        $result = curl_exec($ch);

        return $result;
    }
    
     public function actionTestWmLog()
    {
        $money = [
            "total" => "116",
            "totalCards" => "0",
            "collection" => "116",
            "numberNotes" => "23",
            "numberCoins" => "0"
        ];

        $washAddition = [
            "prewash" => "1",
            "rising_plus" => "1",
            "intensive_washing" => "0",
        ];

        $counters = [
            "water" => "0",
            "power" => "0"
        ];

        $event = [
            "num" => "1",
            "cenBoard" => "0",
            "washer" => "0",
            "dryer" => "0",
            "cleaner" => "0",
            "unitCards" => "0"
        ];

        $pac = [
            "id" => "0",
            "prio" => "0",
            "utc" => "1565299425",
            "devType" => "0",
            "numberDev" => "0",
            "event" => $event,
            "devCash" => "2000",
            "washAddition" => $washAddition,
            "money" => $money,
            "counters" => $counters,
            "priceMode" => "20",
            "tariff" => "0",
            "washMode" => "2",
            "washTemp" => "2",
            "washExtrac" => "1",
            "rssi" => "-70"
        ];

        $data_string = json_encode([
            "imei" => "862643034067094",
            "type" => self::LOG,
            "time" => "1566199266",
            "pac" => $pac
        ]);

        $url = Yii::$app->homeUrl.\yii\helpers\Url::to(['/v2d00/json/index']);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json'
        ));

        $result = curl_exec($ch);

        return $result;
    }
    
}
