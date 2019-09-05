<?php

namespace api\modules\v2d00\UseCase\ImeiInit;

use Exception;
use frontend\models\Imei;
use frontend\models\Jlog;
use frontend\services\custom\Debugger;
use Yii;

class ImeiInit
{
    public function add($items)
    {
        $imei = ImeiInit::getImei($items->imei);
        $imei->imei_central_board = $items->imei;
        $imei->firmware_version = $items->pac->bootloader;
        $imei->firmware_6lowpan = $items->pac->radio;
        $imei->number_channel = $items->pac->channel;
        $imei->pcb_version = (string)$items->pac->PCB;
        $imei->phone_module_number = $items->pac->telephone;
        $imei->level_signal = $items->pac->rssi;
        $imei->on_modem_account = $items->pac->modemCash;
        $imei->traffic = $items->pac->traffic;
        $imei->ping = time() + Jlog::TYPE_TIME_OFFSET;
        
        if (!$imei->update(false)) {
            Yii::$app->response->statusCode = 500;
            throw new Exception('Imei not update!');
        }

        Yii::$app->response->statusCode = 201;
        return 'Imei update';


    }

    /**
     * @param int $imei
     * @return Imei
     * @throws Exception
     */
    public static function getImei(int $imei): Imei
    {
        if (!Imei::find()
                ->where(['imei' => $imei])
                ->andWhere(['status' => Imei::STATUS_ACTIVE])
                ->one()) {
            throw new Exception('Imei not exists!');
        }

        return Imei::findOne(['imei' => $imei]);
    }
}
