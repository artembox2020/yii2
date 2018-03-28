<?php

namespace frontend\controllers;

use frontend\models\dto\ImeiInitDto;
use frontend\models\Imei;
use yii\web\Controller;

class CController extends Controller
{
    const TYPE_PACKET = 'p';

    public function actionI($p)
    {
        $column = [
            'imei',
            'firmware_version',
            'type_bill_acceptance',
            'serial_number_kp',
            'phone_module_number',
            'crash_event_sms',
            'critical_amount',
            'time_out'
        ];
        $array = array_map("str_getcsv", explode('*', $p));
        $arrOut = array();
        foreach ($array as $subArr) {
            $arrOut = array_merge($arrOut, $subArr);
        }
        $result = array_combine($column, $arrOut);

        $initDto = new ImeiInitDto($result);

        if (!empty(Imei::findOne(['imei' => $initDto->imei]))) {
            $imei = Imei::findOne(['imei' => $initDto->imei]);
            $imei->imei_central_board = $initDto->imei;
            $imei->firmware_version = $initDto->firmware_version;
            $imei->type_packet = self::TYPE_PACKET;
            $imei->type_bill_acceptance = $initDto->type_bill_acceptance;
            $imei->serial_number_kp = $initDto->serial_number_kp;
            $imei->phone_module_number = $initDto->phone_module_number;
            $imei->crash_event_sms = $initDto->crash_event_sms;
            $imei->critical_amount = $initDto->critical_amount;
            $imei->time_out = $initDto->time_out;
            $imei->save();
            echo 'Success!';
        }
    }
}
