<?php

namespace frontend\services\parser;

use frontend\controllers\CController;
use frontend\models\ImeiData;
use Yii;

/**
 * parsing initialization and data packet types
 * Class CParser
 * @package frontend\services\parser
 */
class CParser implements CParserInterface
{
    const SEVEN = 7;
    const FOUR = 4;

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

        return $this->getImeiData($p)['imeiData'];
    }

    /**
     * Gets imeiData the packet type data of TYPE_PACKET_DATA
     * 
     * @param $p
     * @return array
     */
    public function getImeiData($p)
    {
        if (empty($p)) {

            return ['imeiData' => null, 'packet' => null];
        }

        $param = explode('_', $p);

        $imeiData = explode(CController::STAR, $param[0]);

        // get index according to packet data version
        $indexOldVersion = $this->getIndexVersionByImeiData($imeiData);

        /** new version for imei */
        $diff = '';
        foreach ($imeiData as $key => $value) {
            if ($key > $indexOldVersion) {
                $diff .= $value . '*';
                unset ($imeiData[$key]);
            }
        }

        $packet = substr($diff, 0, -1);

        $imeiData = CController::setImeiData($imeiData);

        return [
            'imeiData' => $imeiData,
            'packet' => $packet
        ];
    }

    /**
     * Gets index data packet version by imei data
     * 
     * @param array $imeiData
     * @return integer
     */
    public function getIndexVersionByImeiData($imeiData)
    {
        // get timestamp year for old data packet version
        $year = date("Y", (integer)$imeiData[0]);

        // switch index according to packet data version
        if (count($imeiData) >= 8 && (integer)$year > 1969 && (integer)$year < 2100) {

            return $indexOldVersion = self::SEVEN;
        } else {

            return $indexOldVersion = self::FOUR;
        }
    }

    /**
     * Gets cental board status value from 'packet' field (present at tables 'imei_data', 'j_log')
     * 
     * @param string $packet
     * @param ImeiData $imeiData
     * @return string|bool
     */
    public function getCPStatusFromPacketField($packet, $imeiData = false)
    {
        if (empty($packet)) {

            return false;
        }
        
        if (!$imeiData) {
            
            $imeiData = new ImeiData();
        }

        $centalBoardId = explode('*', $packet)[0];

        if (in_array($centalBoardId, array_keys($imeiData->eventCentalBoard))) {

            return Yii::t('imeiData', $imeiData->eventCentalBoard[$centalBoardId]);   
        }

        return false;
    }
    
    /**
     * Gets cental board status value from packet'p'
     * 
     * @param string $p
     * @return string|bool
     */
    public function getCPStatusFromDataPacket($p)
    {
        $packet = $this->getImeiData($p)['packet'];

        if (empty($packet)) {

            return false;
        }

        $centalBoardId = explode('*', $packet)[0];
        $imeiData = new ImeiData();

        if (in_array($centalBoardId, array_keys($imeiData->eventCentalBoard))) {

            return Yii::t('imeiData', $imeiData->eventCentalBoard[$centalBoardId]);   
        }

        return false;
    }

    /**
     * Gets event bill validator value from packet 'p'
     * $useAsCpStatus means to apply EventCentalBoard statuses insead of EventBillValidator ones
     * 
     * @param string $packet
     * @param bool $useAsCpStatus
     * @return string|bool
     */
    public function getEvtBillValidatorFromDataPacket($p, $useAsCpStatus = false)
    {
        $data = $this->getImeiData($p);
        $data = $data['imeiData'];

        if (isset($data['evt_bill_validator'])) {

            if ($useAsCpStatus) {
                $imeiData = new ImeiData();

                if (in_array($data['evt_bill_validator'], array_keys($imeiData->eventCentalBoard))) {

                    return  Yii::t('imeiData', $imeiData->eventCentalBoard[$data['evt_bill_validator']]);
                }

                return false;
            }

            if (in_array($data['evt_bill_validator'], array_keys(ImeiData::evtBillValidator))) {

                return  Yii::t('imeiData', ImeiData::evtBillValidator[$data['evt_bill_validator']]);
            }

            return false;
        }

        return false;
    }

    public function getMashineData($p)
    {
        $array = array();
        $param = explode('_', $p);
        $mashineData = array();

        foreach ($param as $item) {
            if (strripos($item, CController::STAR_DOLLAR)) {
                $item = str_replace(CController::STAR_DOLLAR, '', $item);
            }
            $array[] = explode(CController::STAR, $item);
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

        return $mashineData;
    }
}
