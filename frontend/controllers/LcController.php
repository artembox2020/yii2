<?php

namespace frontend\controllers;

use frontend\models\CbLog;
use frontend\models\dto\CentralBoardDto;
use frontend\models\Imei;
use frontend\services\custom\Debugger;
use yii\web\Controller;

/**
 * Class LcController
 * @package frontend\controllers
 */
class LcController extends Controller
{
    const ONE_CONST = 1;

    public $type_packet = 'central board';

    /**
     * @param $p
     */
    public function actionIndex($p)
    {
        $result = $this->iParse($p);
        $centralBoardDto = new CentralBoardDto($result);

        if (Imei::findOne(['imei' => $centralBoardDto->imei])) {
            $imei = $this->getImei($centralBoardDto->imei);
            if (Imei::getStatus($imei) == self::ONE_CONST) {
                $cbl = new CbLog();
                $cbl->company_id = $imei->company_id;
                $cbl->address_id = $imei->address_id;
                $cbl->imei_id = $imei->id;
                $cbl->date = $centralBoardDto->date;
                $cbl->imei = $centralBoardDto->imei;
                $cbl->unix_time_offset = $centralBoardDto->unix_time_offset;
                $cbl->status = $centralBoardDto->status;
                $cbl->fireproof_counter_hrn = $centralBoardDto->fireproof_counter_hrn;
                $cbl->fireproof_counter_card = $centralBoardDto->fireproof_counter_card;
                $cbl->collection_counter = $centralBoardDto->collection_counter;
                $cbl->notes_billiards_pcs = $centralBoardDto->notes_billiards_pcs;
                $cbl->rate = $centralBoardDto->rate;
                $cbl->refill_amount = $centralBoardDto->refill_amount;
                $cbl->is_deleted = false;
                $cbl->save();
                echo 'cbl data save!';exit;
//                Debugger::d($centralBoardDto);
//                Debugger::dd($cbl);
            } else {
                echo 'Imei not Active';exit;
            }
        } else {
            echo 'Imei not exists';exit;
        }
    }

    /**
     * Parsers the packet type data of TYPE PACKET LOG Central Board
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
            'status',
            'fireproof_counter_hrn',
            'fireproof_counter_card',
            'collection_counter',
            'notes_billiards_pcs',
            'rate',
            'refill_amount'
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

    public function tempPars($p)
    {

    }
}
