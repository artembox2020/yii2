<?php

namespace frontend\controllers;

use frontend\models\dto\LwmDto;
use frontend\models\Imei;
use frontend\models\WmLog;
use frontend\services\custom\Debugger;
use yii\web\Controller;

/**
 * Class LwController
 * @package frontend\controllers
 */
class LwController extends Controller
{

    /** @var int ONE_CONST */
    const ONE_CONST = 1;

    public $type_packet = 'wash machine';

    /**
     * @param $p
     */
    public function actionIndex($p)
    {
        $result = $this->iParse($p);
        $LwmDto = new LwmDto($result);

        if (Imei::findOne(['imei' => $LwmDto->imei])) {
            $imei = $this->getImei($LwmDto->imei);

            if (Imei::getStatus($imei) == self::ONE_CONST) {
                $wml = new WmLog();
                $wml->company_id = $imei->company_id;
                $wml->address_id = $imei->address_id;
                $wml->imei_id = $imei->id;
                $wml->date = $LwmDto->date;
                $wml->imei = $LwmDto->imei;
                $wml->unix_time_offset = $LwmDto->unix_time_offset;
                $wml->number = $LwmDto->number;
                $wml->signal = $LwmDto->signal;
                $wml->status = $LwmDto->status;
                $wml->price = $LwmDto->price;
                $wml->account_money = $LwmDto->account_money;
                $wml->washing_mode = $LwmDto->washing_mode;
                $wml->wash_temperature = $LwmDto->wash_temperature;
                $wml->spin_type = $LwmDto->spin_type;
                $wml->prewash = $LwmDto->prewash;
                $wml->rinsing = $LwmDto->rinsing;
                $wml->intensive_wash = $LwmDto->intensive_wash;
                $wml->is_deleted = false;
                $wml->save();
                echo 'wml data save!';
            } else {
                echo 'Imei not Active';exit;
            }
        } else {
            echo 'Imei not exists';exit;
        }
    }

    /**
     * Parsers the packet type data of TYPE PACKET LOG Wash machine
     *
     * @param $p
     * @return array
     */
    public function iParse($p)
    {
        $arrOut = array();

        $column = [
            'date',
            'imei',
            'unix_time_offset',
            'number',
            'signal',
            'status',
            'price',
            'account_money',
            'washing_mode',
            'wash_temperature',
            'spin_type',
            'prewash',
            'rinsing',
            'intensive_wash'
        ];

        $array = array_map("str_getcsv", explode('*', $p));

        foreach ($array as $subArr) {
            $arrOut = array_merge($arrOut, $subArr);
        }

        $result = array_combine($column, $arrOut);

        return $result;
    }

    /**
     * @param $imei
     * @return Imei|null
     */
    public function getImei($imei)
    {
        return Imei::findOne(['imei' => $imei]);
    }
}
