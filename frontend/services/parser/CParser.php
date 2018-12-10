<?php

namespace frontend\services\parser;

use frontend\controllers\CController;

/**
 * parsing initialization and data packet types
 * Class CParser
 * @package frontend\services\parser
 */
class CParser implements CParserInterface
{
    /**
     * Parses the packet type data of TYPE_PACKET_INITIALIZATION
     * 
     * @param $p
     * @return array|bool
     */
    public function iParse($p)
    {
        $arrOut = array();

        // old initialization packet version
        $column = [
            'imei',
            'firmware_version',
            'firmware_version_cpu',
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

        // new initialization packet version
        $columnNew = [
            'imei',
            'firmware_version',
            'firmware_version_cpu',
            'firmware_6lowpan',
            'number_channel',
            'pcb_version',
            'phone_module_number',
            'on_modem_account',
            'level_signal'
        ];

        $array = array_map("str_getcsv", explode('*', $p));

        foreach ($array as $subArr) {
            $arrOut = array_merge($arrOut, $subArr);
        }

        // pick up the appropriate parser
        if (count($column) == count($arrOut)) {

            $result = array_combine($column, $arrOut);
            $result['on_modem_account'] = null;
            $result['level_signal'] = null;

        } elseif (count($columnNew) == count($arrOut)) {

            $result = array_combine($columnNew, $arrOut);
            $result['type_bill_acceptance'] = null;
            $result['serial_number_kp'] = null;
            $result['crash_event_sms'] = null;
            $result['critical_amount'] = null;
            $result['time_out'] = null;
        } else {

            return false;
        }

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

        // get timestamp year for old data packet version
        $year = date("Y", (integer)$imeiData[0]); 

        // switch index according to packet data version
        if (count($imeiData) >= 8 && (integer)$year > 2000 && (integer)$year < 2100) {
            $indexOldVersion = 7;
        } else {
            $indexOldVersion = 4;
        }

        /** new version for imei */
        foreach ($imeiData as $key => $value) {
            if ($key > $indexOldVersion) {
                unset ($imeiData[$key]);
            }
        }

        return CController::setImeiData($imeiData);
    }
}
