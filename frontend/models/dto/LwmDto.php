<?php

namespace frontend\models\dto;

/**
 * Class LwmDto
 * @package frontend\models\dto
 */
class LwmDto
{
    public $date;
    public $imei;
    public $unix_time_offset;
    public $number;
    public $signal;
    public $status;
    public $price;
    public $account_money;
    public $washing_mode;
    public $wash_temperature;
    public $spin_type;
    public $prewash;
    public $rinsing;
    public $intensive_wash;

    /**
     * map string to LwmDto
     *
     * @param [type] $data
     */
    public function __construct($data)
    {
        if (array_key_exists('date', $data)) {
            $this->date = (double)$data['date'];
        }

        if (array_key_exists('imei', $data)) {
            $this->imei = (integer)$data['imei'];
        }

        if (array_key_exists('unix_time_offset', $data)) {
            $this->unix_time_offset = (double)$data['unix_time_offset'];
        }

        if (array_key_exists('number', $data)) {
            $this->number = (double)$data['number'];
        }

        if (array_key_exists('signal', $data)) {
            $this->signal = (int)$data['signal'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = (int)$data['status'];
        }

        if (array_key_exists('price', $data)) {
            $this->price = (float)$data['price'];
        }

        if (array_key_exists('account_money', $data)) {
            $this->account_money = (float)$data['account_money'];
        }

        if (array_key_exists('washing_mode', $data)) {
            $this->washing_mode = (integer)$data['washing_mode'];
        }

        if (array_key_exists('wash_temperature', $data)) {
            $this->wash_temperature = (integer)$data['wash_temperature'];
        }

        if (array_key_exists('spin_type', $data)) {
            $this->spin_type = (integer)$data['spin_type'];
        }

        if (array_key_exists('prewash', $data)) {
            $this->prewash = (double)$data['prewash'];
        }

        if (array_key_exists('rinsing', $data)) {
            $this->rinsing = (double)$data['rinsing'];
        }

        if (array_key_exists('intensive_wash', $data)) {
            $this->intensive_wash = (double)$data['intensive_wash'];
        }
    }
}
