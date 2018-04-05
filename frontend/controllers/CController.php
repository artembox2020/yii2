<?php

namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Imei;
use frontend\models\dto\DmDto;
use frontend\models\dto\WmDto;
use frontend\models\dto\ImeiDataDto;
use frontend\models\dto\ImeiInitDto;
use frontend\services\custom\Debugger;


class CController extends Controller
{
    const TYPE_PACKET = 'p';

    /**
     * Undocumented function
     * sens.loc/c/i?p=866104020101005*0.1.33*MDB*7070000435*380937777777*380937777777*2*1
     * @param [type] $p
     * @return void
     */
    public function actionI($p)
    {
        $arrOut = array();

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
            $imei->updated_at = date('now');
            $imei->update();
            echo 'Success!';
        }
    }

    /**
     * Undocumented function
     * sense.loc/c/?p=
     * 1467707999*866104020101005*45*110*100*1500*25000*2*1_WM*1*86*15*1*1*22_DM*1*55*45*15_GD*3452*25*5_DC*25*15*-2
     * @param [type] $p
     * @return void
     */
    public function actionD($p)
    {

        $mashineData = array();

        $param = explode('_', $p);

        $imeiData = explode('*', $param[0]);

        foreach ($param as $item) {
            $array[] = explode('*', $item);
        }

        foreach ($array as $key => $value) {
            foreach ($value as $item => $val) {
                if (!is_numeric($val)) {
                    $mashineData[$val][] = $value;
                }
            }
        }

        $result = $this->setImeiData($imeiData);
        // $result = $this->setImeiData($imeiData);
        $mashine = $this->setTypeMashine($mashineData);
        // $mashineDto = new WmDto($mashine);

        $imeiData = new ImeiDataDto($result);

        // Debugger::d($imeiData);
        // Debugger::d($mashine);
    }

    public function setImeiData($data)
    {
        $array_fields = array();
        $arrOut = array();

        $array_fields = [
            'date',
            'imei',
            'level_signal',
            'on_modem_account',
            'in_banknotes',
            'money_in_banknotes',
            'fireproof_residue',
            'price_regime',
            'time_out'
        ];

        return $result = array_combine($array_fields, $data);
    }

    public function setMashine($data)
    {
        $array_fields = array();
        $arrOut = array();

        $array_fields = [
            'number_device',
            'level_signal',
            'bill_cash',
            'door_position',
            'door_block_led',
            'status',
        ];

        return $result = array_combine($array_fields, $data);
    }

    public function setTypeMashine($data)
    {
        if (array_key_exists('WM', $data)) {
            foreach ($data['WM'] as $key => $value) {
                $wm_mashine = new WmDto($this->setWM($data['WM'][$key]));
                // $mashine->save();
                Debugger::d($wm_mashine);
            }
        }

        if (array_key_exists('DM', $data)) {
            foreach ($data['DM'] as $key => $value) {
                $dm_mashine = new DmDto($this->setDM($data['DM'][$key]));
                // $mashine->save();
                Debugger::d($dm_mashine);
            }
        }
    }

    public function setWM($data)
    {
        $array_fields = array();

        $array_fields = [
            'type_mashine',
            'number_device',
            'level_signal',
            'bill_cash',
            'door_position',
            'door_block_led',
            'status',
        ];

        return $result = array_combine($array_fields, $data);
    }

    public function setDM($data)
    {
        $array_fields = array();

        $array_fields = [
            'type_mashine',
            'number_device',
            'level_signal',
            'bill_cash',
            'status',
        ];

        return $result = array_combine($array_fields, $data);
    }
}
