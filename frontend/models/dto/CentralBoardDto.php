<?php

namespace frontend\models\dto;

/**
 * Class CentralBoardDto
 * @package frontend\models\dto
 */
class CentralBoardDto
{
    public $date;
    public $imei;
    public $unix_time_offset;
    public $status;
    public $fireproof_counter_hrn;
    public $fireproof_counter_card;
    public $collection_counter;
    public $notes_billiards_pcs;
    public $rate;
    public $refill_amount;
    public $last_collection_counter;
    public $banknote_face_values;

    /**
     * map string to CentralBoardDto
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

        if (array_key_exists('status', $data)) {
            $this->status = (int)$data['status'];
        }

        if (array_key_exists('fireproof_counter_hrn', $data)) {
            $this->fireproof_counter_hrn = (float)$data['fireproof_counter_hrn'];
        }

        if (
            array_key_exists('fireproof_counter_card', $data)
             && !is_null($data['fireproof_counter_card'])
        ) {
            $this->fireproof_counter_card = (float)$data['fireproof_counter_card'];
        }

        if (array_key_exists('collection_counter', $data)) {
            $this->collection_counter = (float)$data['collection_counter'];
        }

        if (array_key_exists('notes_billiards_pcs', $data)) {
            $this->notes_billiards_pcs = (double)$data['notes_billiards_pcs'];
        }

        if (array_key_exists('rate', $data)) {
            $this->rate = (double)$data['rate'];
        }

        if (
            array_key_exists('refill_amount', $data)
            && !is_null($data['refill_amount'])
        ) {
            $this->refill_amount = (float)$data['refill_amount'];
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
    }
}
