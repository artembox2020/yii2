<?php

namespace frontend\models\dto;

/**
 * Dc dto class
 */
class DcDto
{
    public $serial_number;
    public $type_mashine;
    public $sum_cards;
    public $bill_cash;
    public $status;

    public function __construct($data)
    {

        if (array_key_exists('type_mashine', $data)) {
            $this->type_mashine = (string) $data['type_mashine'];
        }

        if (array_key_exists('sum_cards', $data)) {
            $this->sum_cards = (integer) $data['sum_cards'];
        }

        if (array_key_exists('bill_cash', $data)) {
            $this->bill_cash = (integer) $data['bill_cash'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = (integer) $data['status'];
        }
    }
}