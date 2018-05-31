<?php

namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Imei;
use frontend\models\ImeiData;
use frontend\models\dto\GdDto;
use frontend\models\dto\WmDto;
use frontend\models\GdMashine;
use frontend\models\WmMashine;
use frontend\models\GdMashineData;
use frontend\models\WmMashineData;
use frontend\models\dto\ImeiDataDto;
use frontend\models\dto\ImeiInitDto;
use frontend\services\custom\Debugger;


class CController extends Controller
{
    const TYPE_PACKET = 'p';
    const TYPE_WM = 'WM';
    const TYPE_DM = 'DM';
    const TYPE_DC = 'DC';
    const TYPE_GD = 'GD';
    const STAR = '*';
    const STAR_DOLLAR = '*$';

    /**
     * Initialisation method
     * sens.loc/c/i?p=866104020101005*0.1.33*MDB*7070000435*380937777777*380937777777*2*1
     * @param $p
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
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
     * Initialisation method
     * sense.loc/c/?p=
     * 1467707999*866104020101005*45*110*100*1500*25000*2*1_WM*1*86*15*1*1*22_DM*1*55*45*15_GD*3452*25*5_DC*25*15*-2$
     * $ - ignored
     * @param [type] $p
     * @return void
     */
    public function actionD($p)
    {
        $array = array();
        $mashineData = array();

        $param = explode('_', $p);

        $imeiData = explode(self::STAR, $param[0]);

        foreach ($param as $item) {
            if (strripos($item, self::STAR_DOLLAR)) {
                $item = str_replace(self::STAR_DOLLAR, '', $item);
            }
            $array[] = explode(self::STAR, $item);
        }

        foreach ($array as $key => $value) {
            foreach ($value as $item => $val) {
                if (!is_numeric($val)) {
                    $mashineData[$val][] = $value;
                }
            }
        }

        $result = $this->setImeiData($imeiData);

        $imeiDataDto = new ImeiDataDto($result);
        $imeiData = new ImeiData();
        $imei = Imei::findOne(['imei' => $imeiDataDto->imei]);
        $imeiData->imei_id = $imei->id;
        $imeiData->created_at = $imeiDataDto->date;
        $imeiData->imei = $imeiDataDto->imei;
        $imeiData->level_signal = $imeiDataDto->level_signal;
        $imeiData->on_modem_account = $imeiDataDto->on_modem_account;
        $imeiData->in_banknotes = $imeiDataDto->in_banknotes;
        $imeiData->money_in_banknotes = $imeiDataDto->money_in_banknotes;
        $imeiData->fireproof_residue = $imeiDataDto->fireproof_residue;
        $imeiData->price_regim = $imeiDataDto->price_regim;
        $imeiData->save();

        $imeiId = Imei::findOne(['imei' => $imeiDataDto->imei]);
        $mashine = $this->setTypeMashine($mashineData, $imei->id);
    }

    public function setImeiData($data)
    {
        $array_fields = [
            'date',
            'imei',
            'level_signal',
            'on_modem_account',
            'in_banknotes',
            'money_in_banknotes',
            'fireproof_residue',
            'price_regim',
            'time_out'
        ];

        return $result = array_combine($array_fields, $data);
    }

    public function setMashine($data)
    {
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

    public function setTypeMashine($data, $id)
    {
        if (array_key_exists(self::TYPE_WM, $data)) {
            foreach ($data[self::TYPE_WM] as $key => $value) {
                $wm_mashine_dto = new WmDto($this->setWM($data[self::TYPE_WM][$key]));
                $wm_mashine_data = new WmMashineData();
                $wm_mashine_data->imei_id = $id;
                $wm_mashine_data->type_mashine = $wm_mashine_dto->type_mashine;
                $wm_mashine_data->number_device = $wm_mashine_dto->number_device;
                $wm_mashine_data->level_signal = $wm_mashine_dto->level_signal;
                $wm_mashine_data->bill_cash = $wm_mashine_dto->bill_cash;
                $wm_mashine_data->door_position = $wm_mashine_dto->door_position;
                $wm_mashine_data->door_block_led = $wm_mashine_dto->door_block_led;
                $wm_mashine_data->status = $wm_mashine_dto->status;
                if ($wm_mashine_data->save(false)) {
                    echo 'WM success data save!' . '<br>';
                } else {
                    echo 'Don\'t WM data save' . '<br>';
                }

                if (WmMashine::findOne(['number_device' => $wm_mashine_dto->number_device])) {
                    $wm_mashine = WmMashine::findOne(['number_device' => $wm_mashine_dto->number_device]);
                    $wm_mashine->type_mashine = $wm_mashine_dto->type_mashine;
                    $wm_mashine->number_device = $wm_mashine_dto->number_device;
                    $wm_mashine->level_signal = $wm_mashine_dto->level_signal;
                    $wm_mashine->bill_cash = $wm_mashine_dto->bill_cash;
                    $wm_mashine->door_position = $wm_mashine_dto->door_position;
                    $wm_mashine->door_block_led = $wm_mashine_dto->door_block_led;
                    $wm_mashine->status = $wm_mashine_dto->status;
                    if ($wm_mashine->update()) {
                        echo 'WM success update!' . '<br>';
                    } else {
                        echo 'WM status has not changed' . '<br>';
                    }
                } else {
                    $wm_mashine_new = new WmMashine();
                    $wm_mashine_new->imei_id = $id;
                    $wm_mashine_new->type_mashine = $wm_mashine_dto->type_mashine;
                    $wm_mashine_new->number_device = $wm_mashine_dto->number_device;
                    $wm_mashine_new->level_signal = $wm_mashine_dto->level_signal;
                    $wm_mashine_new->bill_cash = $wm_mashine_dto->bill_cash;
                    $wm_mashine_new->door_position = $wm_mashine_dto->door_position;
                    $wm_mashine_new->door_block_led = $wm_mashine_dto->door_block_led;
                    $wm_mashine_new->status = $wm_mashine_dto->status;
                    if ($wm_mashine_new->save()) {
                        echo 'WM success save!' . '<br>';
                    } else {
                        echo 'Don\'t save' . '<br>';
                    }
                }
            }

        }

        if (array_key_exists(self::TYPE_GD, $data)) {
            foreach ($data[self::TYPE_GD] as $key => $value) {
                $gd_mashine_dto = new GdDto($this->setGd($data[self::TYPE_GD][$key]));
                $gd_mashine_data = new GdMashineData();
                $gd_mashine_data->imei_id = $id;
                $gd_mashine_data->type_mashine = $gd_mashine_dto->type_mashine;
                $gd_mashine_data->gel_in_tank = $gd_mashine_dto->gel_in_tank;
                $gd_mashine_data->bill_cash = $gd_mashine_dto->bill_cash;
                $gd_mashine_data->status = $gd_mashine_dto->status;
                if ($gd_mashine_data->save()) {
                    echo 'GD data success save!' . '<br>';
                } else {
                    echo 'GD data Don\'t save' . '<br>';
                }

                if (GdMashine::findOne(['imei_id' => $id])) {
                    $gd_mashine = GdMashine::findOne(['imei_id' => $id]);
                    $gd_mashine->imei_id = $id;
                    $gd_mashine->type_mashine = $gd_mashine_dto->type_mashine;
                    $gd_mashine->gel_in_tank = $gd_mashine_dto->gel_in_tank;
                    $gd_mashine->bill_cash = $gd_mashine_dto->bill_cash;
                    $gd_mashine->status = $gd_mashine_dto->status;
                    if ($gd_mashine->update()) {
                        echo 'GD success update!' . '<br>';
                    } else {
                        echo 'GD status has not changed' . '<br>';
                    }
                }
            }
        }

        /** does not work! */
        // if (array_key_exists(self::TYPE_DC, $data)) {
        //     foreach ($data[self::TYPE_DC] as $key => $value) {
        //         $gd_mashine = new DcDto($this->setDC($data[self::TYPE_DC][$key]));
        //         // $mashine->save();
        //         // Debugger::d($gd_mashine);
        //     }
        // }

        // if (array_key_exists(self::TYPE_DM, $data)) {
        //     foreach ($data[self::TYPE_DM] as $key => $value) {
        //         $dm_mashine = new DmDto($this->setDM($data[self::TYPE_DM][$key]));
        //         // $mashine->save();
        //         // Debugger::d($dm_mashine);
        //     }
        // }
        /** does not work! */
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

    public function setGd($data)
    {
        $array_fields = array();

        $array_fields = [
            'type_mashine',
            'gel_in_tank',
            'bill_cash',
            'status',
        ];

        return $result = array_combine($array_fields, $data);
    }

    /**
     * method setDC() does not work!
     *
     * @param [type] $data
     * @return void
     */
    public function setDC($data)
    {
        $array_fields = array();

        $array_fields = [
            'type_mashine',
            'sum_cards',
            'bill_cash',
            'status',
        ];

        return $result = array_combine($array_fields, $data);
    }

    /**
     *  method setDM() does not work!
     * 
     * @param [type] $data
     * @return void
     */
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
