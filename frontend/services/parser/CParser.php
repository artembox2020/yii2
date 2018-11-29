<?php

namespace frontend\services\parser;

use frontend\controllers\CController;

/**
 * Class CParser
 * @package frontend\services\parser
 */
class CParser implements CParserInterface
{
    /**
     * Parsers the packet type data of TYPE_PACKET_INITIALIZATION
     * 
     * @param $p
     * @return array|bool
     */
    public function iParse($p)
    {
        $arrOut = array();

        $column = [
            'imei',
            'firmware_version_cpu',
            'firmware_version',
            'firmware_6lowpan',
            'number_channel',
            'pcb_version',
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

        if (count($arrOut) != count($column)) {

            return false;
        }

        $result = array_combine($column, $arrOut);

        return $result;
    }

    /**
     * Parsers the packet type data of TYPE_PACKET_DATA
     * 
     * @param $p
     * @return array
     */
    public function dParse($p)
    {
        $param = explode('_', $p);

        $imeiData = explode(CController::STAR, $param[0]);

        /** new version for imei */
        foreach ($imeiData as $key => $value) {
            if ($key > 7) {
                unset ($imeiData[$key]);
            }
        }

        return CController::setImeiData($imeiData);
    }
}
