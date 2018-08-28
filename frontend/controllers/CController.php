<?php

namespace frontend\controllers;

use frontend\services\imei\ImeiService;
use frontend\services\parser\CParser;
use Yii;
use yii\helpers\ArrayHelper;
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
use frontend\models\Jlog;
use frontend\services\custom\Debugger;

/**
 * data processing IMEI, Wash - Machine, Gel - Dispenser
 * Class CController
 * @package frontend\controllers
 */
class CController extends Controller
{
    const TYPE_PACKET = 'p';
    const TYPE_WM = 'WM';
    const TYPE_DM = 'DM';
    const TYPE_DC = 'DC';
    const TYPE_GD = 'GD';
    const STAR = '*';
    const STAR_DOLLAR = '*$';
    const ONE_CONST = 1;

    /**
     * Initialisation method
     * sens.loc/c/i?p=862631033023192*1.60*1.11*2.00*MDB**380733108207*+380936769548*3*60
     * @param $p
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionI($p)
    {
        $cParser = new CParser();
        $result = $cParser->iParse($p);
        $initDto = new ImeiInitDto($result);

        if (Imei::findOne(['imei' => $initDto->imei])) {
            $imei = Imei::findOne(['imei' => $initDto->imei]);

            if (Imei::getStatus($imei) == self::ONE_CONST) {
                $imei = Imei::findOne(['imei' => $initDto->imei]);
                $imei->imei_central_board = $initDto->imei;
                $imei->firmware_version = $initDto->firmware_version;
                $imei->firmware_version_cpu = $initDto->firmware_version_cpu;
                $imei->firmware_6lowpan = $initDto->firmware_6lowpan;
                $imei->type_packet = self::TYPE_PACKET;
                $imei->type_bill_acceptance = $initDto->type_bill_acceptance;
                $imei->serial_number_kp = $initDto->serial_number_kp;
                $imei->phone_module_number = $initDto->phone_module_number;
                $imei->crash_event_sms = $initDto->crash_event_sms;
                $imei->critical_amount = $initDto->critical_amount;
                $imei->time_out = $initDto->time_out;
                $imei->ping = $initDto->date;
                $imei->update();

                $jlog = new Jlog();
                $jlog->createLogFromImei($imei, $p, Jlog::TYPE_PACKET_INITIALIZATION);
                echo 'Success!';
            } else {
                echo 'Imei not Active';exit;
            }
        } else {
            echo 'Imei not exists';exit;
        }
    }

    /**
     * Data method
     * sens.loc/c/d?p=
     * 1467707999*866104020101005*45*110*100*1500*25000*2*1_WM*1*86*15*1*1*22_DM*1*55*45*15_GD*3452*25*5_DC*25*15*-2$
     * $ - ignored
     * @param [type] $p
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
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

        /**
         * allocate the machine to an array $mashineData
         */
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

        if (Imei::findOne(['imei' => $imeiDataDto->imei])) {

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
            $imeiData->is_deleted = false;

            $imeiData->save();
            
            $imei->ping = $imeiDataDto->date;
            $imei->save();
            
            $jlog = new Jlog();
            $jlog->createLogFromImei($imei, $p, Jlog::TYPE_PACKET_DATA);

            $this->setTypeMashine($mashineData, $imei);

        } else {
            echo 'Imei not init or not exists'; exit;
        }
    }

    /**
     * @param $data
     * @return array
     */
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
//            'time_out'
        ];

        return $result = array_combine($array_fields, $data);
    }

    /**
     * @param $data array
     * $id - Imei->id
     * @param Imei $imei
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function setTypeMashine($data, Imei $imei)
    {
        /** create or update wash machine (WM) */
        if (array_key_exists(self::TYPE_WM, $data)) {
            $this->serviceWashMachine($data, $imei);
        }

        /** create or update gel dispenser (GD) */
        if (array_key_exists(self::TYPE_GD, $data)) {
            $this->serviceGelDispenser($data, $imei->id);
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

    /**
     * @param $data
     * @return array
     */
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
            'current_status',
            'display',
        ];

        return $result = array_combine($array_fields, $data);
    }

    /**
     * @param $data
     * @return array
     */
    public function setGd($data)
    {
        $array_fields = array();

        $array_fields = [
            'type_mashine',
            'gel_in_tank',
            'bill_cash',
            'current_status',
        ];

        return $result = array_combine($array_fields, $data);
    }

    /**
     * method setDC() does not work!
     *
     * @param [type] $data
     * @return array
     */
    public function setDC($data)
    {
        $array_fields = array();

        $array_fields = [
            'type_mashine',
            'sum_cards',
            'bill_cash',
            'current_status',
        ];

        return $result = array_combine($array_fields, $data);
    }

    /**
     *  method setDM() does not work!
     * 
     * @param [type] $data
     * @return array
     */
    public function setDM(array $data): array
    {
        $array_fields = array();

        $array_fields = [
            'type_mashine',
            'number_device',
            'level_signal',
            'bill_cash',
            'current_status',
        ];

        return $result = array_combine($array_fields, $data);
    }

    /**
     * @param $wm_machine_dto
     * @param $imei_id
     * @return WmMashine
     */
    public function autoCreateWashMachine($wm_machine_dto, $imei_id)
    {
        $imei = $this->getImei($imei_id);

        $wash_machine = new WmMashine();
        $wash_machine->type_mashine = $wm_machine_dto->type_mashine;
        $wash_machine->number_device = $wm_machine_dto->number_device;
        $wash_machine->level_signal = $wm_machine_dto->level_signal;
        $wash_machine->bill_cash = $wm_machine_dto->bill_cash;
        $wash_machine->door_position = $wm_machine_dto->door_position;
        $wash_machine->door_block_led = $wm_machine_dto->door_block_led;
        $wash_machine->current_status = $wm_machine_dto->current_status;
        $wash_machine->imei_id = $imei_id;
        $wash_machine->company_id = $imei->company_id;
        $wash_machine->balance_holder_id = $imei->balance_holder_id;
        $wash_machine->address_id = $imei->address_id;
        $wash_machine->current_status = $wm_machine_dto->current_status;
        $wash_machine->status = self::ONE_CONST;
        $wash_machine->is_deleted = false;
        $wash_machine->display = $wm_machine_dto->display;
        $wash_machine->save(false);

        return $wash_machine;
    }

    /**
     * @param $wm_mashine_dto
     * @param $imei_id
     * @return GdMashine
     */
    public function autoCreateGelDispenser($gd_mashine_dto, $imei_id)
    {
        $imei = $this->getImei($imei_id);

        $gd_mashine = new GdMashine();
        $gd_mashine->imei_id = $imei_id;
        $gd_mashine->company_id = $imei->company_id;
        $gd_mashine->balance_holder_id = $imei->balance_holder_id;
        $gd_mashine->address_id = $imei->address_id;
        $gd_mashine->current_status = self::ONE_CONST;
        $gd_mashine->is_deleted = false;
        $gd_mashine->type_mashine = $gd_mashine_dto->type_mashine;
        $gd_mashine->gel_in_tank = $gd_mashine_dto->gel_in_tank;
        $gd_mashine->bill_cash = $gd_mashine_dto->bill_cash;
        $gd_mashine->current_status = $gd_mashine_dto->current_status;
        $gd_mashine->is_deleted = false;
        $gd_mashine->save(false);

        return $gd_mashine;
    }

    /**
     * @param $imei_id
     * @return Imei|null
     */
    public function getImei($imei_id)
    {
        $imei = Imei::findOne(['id' => $imei_id ]);

        return $imei;
    }

    /**
     * @param $data
     * @param $imei
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function serviceWashMachine($data, $imei)
    {
        foreach ($data[self::TYPE_WM] as $key => $value) {
            $wm_mashine_dto = new WmDto($this->setWM($data[self::TYPE_WM][$key]));

            if (!WmMashine::find()
            ->where(['number_device' => $wm_mashine_dto->number_device])
            ->andWhere(['imei_id' => $imei->id])->one()) {
                $this->autoCreateWashMachine($wm_mashine_dto, $imei->id);
                     echo $wm_mashine_dto->number_device . ' WM created!' . '<br>';
            }

            if (WmMashine::find()
                ->where(['number_device' => $wm_mashine_dto->number_device])
                ->andWhere(['imei_id' => $imei->id])->one())  {
                $wm_mashine = WmMashine::find()
                    ->where(['number_device' => $wm_mashine_dto->number_device])
                    ->andWhere(['imei_id' => $imei->id])->one();
                $wm_mashine_data = new WmMashineData();
                $wm_mashine_data->mashine_id = $wm_mashine->id;
                $wm_mashine_data->type_mashine = $wm_mashine_dto->type_mashine;
                $wm_mashine_data->number_device = $wm_mashine_dto->number_device;
                $wm_mashine_data->level_signal = $wm_mashine_dto->level_signal;
                $wm_mashine_data->bill_cash = $wm_mashine_dto->bill_cash;
                $wm_mashine_data->door_position = $wm_mashine_dto->door_position;
                $wm_mashine_data->door_block_led = $wm_mashine_dto->door_block_led;
                $wm_mashine_data->current_status = $wm_mashine_dto->current_status;
                $wm_mashine_data->status = self::ONE_CONST;
                $wm_mashine_data->is_deleted = false;
                $wm_mashine_data->display = $wm_mashine_dto->display;
                $wm_mashine_data->ping = $imei->ping;
//                Debugger::dd($wm_mashine_data->display);
                $this->updateWmMashine($wm_mashine, $wm_mashine_data);
                if ($wm_mashine_data->save(false)) {
                    echo $wm_mashine_data->number_device . ' WM data save!' . '<br>';
                } else {
                    echo 'WM data Don\'t save' . '<br>';
                }
            }

//            $this->updateWmDeviceNumberNull($wm_mashine_dto, $imei->id);
        }
    }

    /**
     * @param $wm_mashine
     * @param WmMashineData $wm_mashine_data
     */
    public function updateWmMashine($wm_mashine, $wm_mashine_data)
    {
        $wm_mashine_data->mashine_id = $wm_mashine->id;
        $wm_mashine->type_mashine = $wm_mashine_data->type_mashine;
        $wm_mashine->number_device = $wm_mashine_data->number_device;
        $wm_mashine->level_signal = $wm_mashine_data->level_signal;
        $wm_mashine->bill_cash = $wm_mashine_data->bill_cash;
        $wm_mashine->door_position = $wm_mashine_data->door_position;
        $wm_mashine->door_block_led = $wm_mashine_data->door_block_led;
        $wm_mashine->current_status = $wm_mashine_data->current_status;
        $wm_mashine->display = $wm_mashine_data->display;
        $wm_mashine_data->is_deleted = false;
        $wm_mashine->ping = $wm_mashine_data->ping;
        $wm_mashine->update(false);
        echo $wm_mashine->number_device . ' WM updated!' . '<br>';
    }

//    public function updateWmDeviceNumberNull($wm_mashine_dto, $imei_id)
//    {
//        $imei = $this->getImei($imei_id);
//
////        Debugger::dd($imei);
//        Debugger::dd($wm_mashine_dto->number_device);
//    }

    /**
     * @param $gd_mashine
     * @param GdMashineData $gd_mashine_data
     */
    public function updateGdMashine($gd_mashine, $gd_mashine_data)
    {
        $gd_mashine_data->mashine_id = $gd_mashine->id;
        $gd_mashine->type_mashine = $gd_mashine_data->type_mashine;
        $gd_mashine->gel_in_tank = $gd_mashine_data->gel_in_tank;
        $gd_mashine->bill_cash = $gd_mashine_data->bill_cash;
        $gd_mashine->current_status = $gd_mashine_data->current_status;
        $gd_mashine_data->is_deleted = false;
        $gd_mashine->update(false);
        echo 'GD updated!' . '<br>';
    }

    /**
     * @param array $data
     * @param int $imei_id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function serviceGelDispenser($data, $imei_id)
    {
        foreach ($data[self::TYPE_GD] as $key => $value) {
            $gd_mashine_dto = new GdDto($this->setGd($data[self::TYPE_GD][$key]));

            if (!GdMashine::find()
                ->where(['imei_id' => $imei_id])->one()) {
                $this->autoCreateGelDispenser($gd_mashine_dto, $imei_id);
                echo 'Gel Dispenser created!' . '<br>';
            }

            if (GdMashine::find()
                ->where(['imei_id' => $imei_id])->one()) {
                $gd_mashine = GdMashine::find()
                    ->where(['imei_id' => $imei_id])->one();

                $gd_mashine_data = new GdMashineData();
                $gd_mashine_data->mashine_id = $gd_mashine->id;
                $gd_mashine_data->type_mashine = $gd_mashine_dto->type_mashine;
                $gd_mashine_data->gel_in_tank = $gd_mashine_dto->gel_in_tank;
                $gd_mashine_data->bill_cash = $gd_mashine_dto->bill_cash;
                $gd_mashine_data->current_status = $gd_mashine_dto->current_status;
                $gd_mashine_data->is_deleted = false;

                $this->updateGdMashine($gd_mashine, $gd_mashine_data);

                if ($gd_mashine_data->save(false)) {
                    echo 'Gel Dispenser data save!' . '<br>';
                } else {
                    echo 'Gel Dispenser data Don\'t save' . '<br>';
                }
            }
        }
    }
}
