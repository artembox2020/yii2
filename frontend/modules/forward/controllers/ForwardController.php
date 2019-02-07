<?php

namespace frontend\modules\forward\controllers;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\WmMashine;
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
     * @param $address_name
     * @return \yii\web\Response
     */
    public function actionIndex($address_name)
    {
        $array = array();
        $result = array();

        $address = AddressBalanceHolder::find()->where(['name' => $address_name])->one();
        $imei = Imei::find()->where(['address_id' => $address->id])
            ->andWhere(['imei.status' => Imei::STATUS_ACTIVE])->one();
        $wm_machine = WmMashine::find()->where(['imei_id' => $imei->id])
            ->andWhere(['wm_mashine.status' => WmMashine::STATUS_ACTIVE])->all();

        foreach ($wm_machine as $key => $value) {
            $array[$value->number_device] = [
                'id' => $value->number_device,
                'display' => $value->display,
                'status' => $value->current_status];
        }

        $result[$address->address . ' ' . $address->floor] = $array;

        return $this->asJson($result);
    }
}
