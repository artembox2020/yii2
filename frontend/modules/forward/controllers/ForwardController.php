<?php

namespace frontend\modules\forward\controllers;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\ImeiData;
use frontend\models\WmMashine;
use frontend\modules\forward\service\ServiceForward;
use frontend\modules\forward\service\StateImeiData;
use frontend\services\custom\Debugger;
use Yii;
use yii\web\Controller;

/**
 * forward/forward/index?address_name=short address name
 *
 * Class ForwardController
 * @package frontend\modules\forward\controllers
 */
class ForwardController extends Controller
{

    /**
     * @param string $address_name
     * @return string
     */
    public function actionIndex(string $address_name)
    {
        $service = new ServiceForward();
        $result = $service->getStaff($address_name);

        return $this->render('index', [
            'result' => $result
        ]);

//        return $this->asJson($result);
    }
}
