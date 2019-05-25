<?php

namespace frontend\models\dto;

/**
 * Gd dto class
 */
class GdDto
{
    public $serial_number;
    public $type_mashine;
    public $gel_in_tank;
    public $bill_cash;
    public $current_status;

    public function __construct($data)
    {

        if (array_key_exists('type_mashine', $data)) {
            $this->type_mashine = (string) $data['type_mashine'];
        }

        if (array_key_exists('gel_in_tank', $data)) {
            $this->gel_in_tank = (integer) $data['gel_in_tank'];
        }

        if (array_key_exists('bill_cash', $data)) {
            $this->bill_cash = (integer) $data['bill_cash'];
        }

        if (array_key_exists('current_status', $data)) {
            $this->current_status = (integer) $data['current_status'];
        }
    }
}
