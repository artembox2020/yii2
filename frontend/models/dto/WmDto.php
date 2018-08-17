<?php

namespace frontend\models\dto;

/**
 * WM dto class
 */
class WmDto
{
    public $serial_number;
    public $type_mashine;
    public $number_device;
    public $level_signal;
    public $bill_cash;
    public $door_position;
    public $door_block_led;
    public $current_status;
    public $display;

    public function __construct($data)
    {
        if (array_key_exists('number_device', $data)) {
            $this->number_device = (double) $data['number_device'];
        }

        if (array_key_exists('type_mashine', $data)) {
            $this->type_mashine = (string) $data['type_mashine'];
        }

        if (array_key_exists('level_signal', $data)) {
            $this->level_signal = (integer) $data['level_signal'];
        }

        if (array_key_exists('bill_cash', $data)) {
            $this->bill_cash = (float) $data['bill_cash'];
        }

        if (array_key_exists('door_position', $data)) {
            $this->door_position = (double) $data['door_position'];
        }

        if (array_key_exists('door_block_led', $data)) {
            $this->door_block_led = (double) $data['door_block_led'];
        }

        if (array_key_exists('current_status', $data)) {
            $this->current_status = (integer) $data['current_status'];
        }

        if (array_key_exists('display', $data)) {
            $this->display = (string) $data['display'];
        }
    }
}
