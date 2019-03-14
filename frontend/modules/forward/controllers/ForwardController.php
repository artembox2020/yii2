<?php

namespace frontend\modules\forward\controllers;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\ImeiData;
use frontend\models\WmMashine;
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

    public function actionIndex($address_name)
    {
        $array = array();
        $result = array();

        $address = AddressBalanceHolder::find()
            ->andWhere(['name' => $address_name])
            ->one();

        $imei = Imei::find()
            ->andWhere(['address_id' => $address->id])
            ->andWhere(['imei.status' => Imei::STATUS_ACTIVE])
            ->one();

        $wm_machine = WmMashine::find()
            ->andWhere(['imei_id' => $imei->id])
            ->andWhere(['wm_mashine.status' => WmMashine::STATUS_ACTIVE])
            ->all();

//        $id = $imei->id;
//        $res = Yii::$app->db->createCommand('SELECT * FROM imei_data WHERE imei_id = :id ORDER BY created_at DESC')->bindParam(':id', $id)->queryOne( \PDO::FETCH_OBJ);
          $imeiData = ImeiData::find()
              ->andWhere(['imei_id' => $imei->id])
              ->orderBy('created_at DESC')
              ->one();

        $result['param'] = [
            'central_board_status' => Yii::t('imeiData', $imeiData->status_central_board[$imeiData->packet]),
            'bill_acceptor_status' => Yii::t('imeiData', $imeiData->status_bill_acceptor[$imeiData->evt_bill_validator])
        ];

        foreach ($wm_machine as $key => $value) {
            $array[$value->number_device] = [
                'device_number' => $value->number_device,
                'display' => $value->display,
                'status' => Yii::t('frontend', $value->current_state[$value->current_status])];
        }

        $result[ $address->address . ' ' . $address->floor] = $array;
//        Debugger::d($result);

        return $this->asJson($result);
    }
}
