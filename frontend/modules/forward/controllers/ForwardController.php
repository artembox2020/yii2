<?php

namespace frontend\modules\forward\controllers;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\WmMashine;
use yii\web\Controller;

/**
 * Class ForwardController
 * @package frontend\modules\forward\controllers
 */
class ForwardController extends Controller
{
    /**
     * /forward/forward/index?address_name=short address name
     * 
     * @param $address_name
     * @return \yii\web\Response
     */
    public function actionIndex($address_name)
    {
        $array = array();
        $address = AddressBalanceHolder::findOne(['name' => $address_name]);
        $imei = Imei::find(['address_id' => $address->id])
            ->andWhere(['imei.status' => Imei::STATUS_ACTIVE])->one();
        $wm_machine = WmMashine::find(['imei_id' => $imei->id])
            ->andWhere(['wm_mashine.status' => WmMashine::STATUS_ACTIVE])->all();

        foreach ($wm_machine as $key => $value) {
            $array[$value->number_device] = $value->display;
        }

        return $this->asJson($array);
    }
}
