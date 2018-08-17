<?php

namespace frontend\models\dto;

/**
 * Imei data Dto
 */
class ImeiDataDto
{
    public $date;
    public $imei;
    public $level_signal;
    public $on_modem_account;
    public $in_banknotes;
    public $money_in_banknotes;
    public $fireproof_residue;
    public $price_regim;
//    public $tiem_out;

    public function __construct($data)
    {
        if (array_key_exists('date', $data)) {
            $this->date = (integer)$data['date'];
        }

        if (array_key_exists('imei', $data)) {
            $this->imei = (string)$data['imei'];
        }

        if (array_key_exists('level_signal', $data)) {
            $this->level_signal = (integer)$data['level_signal'];
        }

        if (array_key_exists('on_modem_account', $data)) {
            $this->on_modem_account = (integer)$data['on_modem_account'];
        }

        if (array_key_exists('in_banknotes', $data)) {
            $this->in_banknotes = (integer)$data['in_banknotes'];
        }

        if (array_key_exists('money_in_banknotes', $data)) {
            $this->money_in_banknotes = (integer)$data['money_in_banknotes'];
        }

        if (array_key_exists('fireproof_residue', $data)) {
            $this->fireproof_residue = (integer)$data['fireproof_residue'];
        }

        if (array_key_exists('price_regim', $data)) {
            $this->price_regim = (integer)$data['price_regim'];
        }

//        if (array_key_exists('time_out', $data)) {
//            $this->time_out = (integer)$data['time_out'];
//        }
    }
}
