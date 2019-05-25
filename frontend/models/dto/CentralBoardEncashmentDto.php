<?php

namespace frontend\models\dto;

/**
 * Class CentralBoardEncashmentDto
 * @package frontend\models\dto
 */
class CentralBoardEncashmentDto
{
    public $imei;
    public $unix_time_offset;
    public $fireproof_counter_hrn;
    public $collection_counter;
    public $notes_billiards_pcs;
    public $last_collection_counter;
    public $banknote_face_values;
    public $amount_of_coins;
    public $coin_face_values;

    /**
     * map string to CentralBoardEncashmentDto
     *
     * @param [type] $data
     */
    public function __construct($data)
    {
        if (array_key_exists('imei', $data)) {
            $this->imei = (integer)$data['imei'];
        }

        if (array_key_exists('unix_time_offset', $data)) {
            $this->unix_time_offset = (double)$data['unix_time_offset'];
        }

        if (array_key_exists('fireproof_counter_hrn', $data)) {
            $this->fireproof_counter_hrn = (float)$data['fireproof_counter_hrn'];
        }

        if (array_key_exists('collection_counter', $data)) {
            $this->collection_counter = (float)$data['collection_counter'];
        }

        if (array_key_exists('notes_billiards_pcs', $data)) {
            $this->notes_billiards_pcs = (double)$data['notes_billiards_pcs'];
        }

        if (
            array_key_exists('last_collection_counter', $data) 
            && !is_null($data['last_collection_counter'])
        ) {
            $this->last_collection_counter = (float)$data['last_collection_counter'];
        }

        if (array_key_exists('banknote_face_values', $data)) {
            $this->banknote_face_values = (string)$data['banknote_face_values'];
        }

        if (array_key_exists('amount_of_coins', $data)) {
            $this->amount_of_coins = (float)$data['amount_of_coins'];
        }

        if (array_key_exists('coin_face_values', $data)) {
            $this->coin_face_values = (string)$data['coin_face_values'];
        }
    }
}
