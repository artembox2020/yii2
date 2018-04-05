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
    public $price_regime;

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

        if (array_key_exists('price_regime', $data)) {
            $this->price_regime = (integer)$data['price_regime'];
        }
    }
}