<?php

namespace frontend\models\dto;

/**
 * DM dto class
 */
class DсDto
{
    public $serial_number;
    public $type_mashine;
    public $number_device;
    public $level_signal;
    public $bill_cash;
    public $status;

    public function __construct($data)
    {
        if (array_key_exists('number_device', $data)) {
            $this->number_device = (integer) $data['number_device'];
        }

        if (array_key_exists('type_mashine', $data)) {
            $this->type_mashine = (string) $data['type_mashine'];
        }

        if (array_key_exists('level_signal', $data)) {
            $this->level_signal = (integer) $data['level_signal'];
        }

        if (array_key_exists('bill_cash', $data)) {
            $this->bill_cash = (integer) $data['bill_cash'];
        }

        if (array_key_exists('door_position', $data)) {
            $this->door_position = (integer) $data['door_position'];
        }

        if (array_key_exists('door_block_led', $data)) {
            $this->door_block_led = (integer) $data['door_block_led'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = (integer) $data['status'];
        }
    }
}