<?php

namespace frontend\models\dto;

/**
 * Imei initialization Dto
 */
class ImeiInitDto
{
    public $date;
    public $imei;
    public $level_signal;
    public $firmware_version;
    public $type_bill_acceptance;
    public $serial_number_kp;
    public $phone_module_number;
    public $crash_event_sms;
    public $critical_amount;
    public $time_out;
    public $firmware_version_cpu;
    public $firmware_6lowpan;
    public $number_channel;
    public $pcb_version;
    public $on_modem_account;

    /**
     * map string to ImeiInitDto
     *
     * @param [type] $data
     */
    public function __construct($data)
    {
        if (array_key_exists('date', $data)) {
            $this->date = (string)$data['date'];
        }

        if (array_key_exists('imei', $data)) {
            $this->imei = (integer)$data['imei'];
        }

        if (array_key_exists('level_signal', $data)) {
            $this->level_signal = (string)$data['level_signal'];
        }

        if (array_key_exists('firmware_version', $data)) {
            $this->firmware_version = (string)$data['firmware_version'];
        }

        if (array_key_exists('type_bill_acceptance', $data)) {
            $this->type_bill_acceptance = (string)$data['type_bill_acceptance'];
        }

        if (array_key_exists('serial_number_kp', $data)) {
            $this->serial_number_kp = (string)$data['serial_number_kp'];
        }

        if (array_key_exists('phone_module_number', $data)) {
            $this->phone_module_number = (string)$data['phone_module_number'];
        }

        if (array_key_exists('crash_event_sms', $data)) {
            $this->crash_event_sms = (string)$data['crash_event_sms'];
        }

        if (array_key_exists('critical_amount', $data)) {
            $this->critical_amount = (int)$data['critical_amount'];
        }

        if (array_key_exists('time_out', $data)) {
            $this->time_out = (int)$data['time_out'];
        }

        if (array_key_exists('firmware_6lowpan', $data)) {
            $this->firmware_6lowpan = (float)$data['firmware_6lowpan'];
        }

        if (array_key_exists('firmware_version_cpu', $data)) {
            $this->firmware_version_cpu = (string)$data['firmware_version_cpu'];
        }

        if (array_key_exists('number_channel', $data)) {
            $this->number_channel = (string)$data['number_channel'];
        }

        if (array_key_exists('pcb_version', $data)) {
            $this->pcb_version = (string)$data['pcb_version'];
        }

        if (array_key_exists('on_modem_account', $data)) {
            $this->on_modem_account = (float)$data['on_modem_account'];
        }
    }
}
