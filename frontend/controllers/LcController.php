<?php

namespace frontend\controllers;

use frontend\models\dto\CentralBoardDto;
use frontend\services\custom\Debugger;
use yii\web\Controller;

class LcController extends Controller
{
    public function actionIndex($p)
    {
        $result = $this->iParse($p);
        $centralBoardDto = new CentralBoardDto($result);
        Debugger::dd($centralBoardDto);
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
}
