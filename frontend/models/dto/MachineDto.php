<?php

namespace frontend\models\dto;

class MachineDto
{
    public $edate;
    public $imei;
    public $gsmSignal;
    public $billModem;
    public $numBills;
    public $sumBills;
    public $ost;
    public $price;
    public $type;
    public $numDev;
    public $devSignal;
    public $billCash;
    public $doorpos;
    public $doorled;
    public $statusDev;
    public $type2;
    public $numDev2;
    public $devSignal2;
    public $billCash2;
    public $statusDev2;
    public $type3;
    public $colGel3;
    public $billCash3;
    public $statusDev3;
    public $type4;
    public $colCart4;
    public $billCash4;
    public $statusDev4;

    public function __construct($data)
    {
        if (array_key_exists('edate', $data)) {
            $this->edate = (int)$data['edate'];
        }

        if (array_key_exists('imei', $data)) {
            $this->imei = (int)$data['imei'];
        }

        if (array_key_exists('gsmSignal', $data)) {
            $this->gsmSignal = (int)$data['gsmSignal'];
        }

        if (array_key_exists('billModem', $data)) {
            $this->billModem = (int)$data['billModem'];
        }

        if (array_key_exists('numBills', $data)) {
            $this->numBills = (int)$data['numBills'];
        }

        if (array_key_exists('sumBills', $data)) {
            $this->sumBills = (int)$data['sumBills'];
        }

        if (array_key_exists('ost', $data)) {
            $this->ost = (int)$data['ost'];
        }

        if (array_key_exists('price', $data)) {
            $this->price = (int)$data['price'];
        }

        if (array_key_exists('type', $data)) {
            $this->type = (string)$data['type'];
        }

        if (array_key_exists('numDev', $data)) {
            $this->numDev = (int)$data['numDev'];
        }

        if (array_key_exists('devSignal', $data)) {
            $this->devSignal = (int)$data['devSignal'];
        }

        if (array_key_exists('billCash', $data)) {
            $this->billCash = (int)$data['billCash'];
        }

        if (array_key_exists('doorpos', $data)) {
            $this->doorpos = (int)$data['doorpos'];
        }

        if (array_key_exists('doorled', $data)) {
            $this->doorled = (int)$data['doorled'];
        }

        if (array_key_exists('statusDev', $data)) {
            $this->statusDev = (int)$data['statusDev'];
        }

        if (array_key_exists('type2', $data)) {
            $this->type2 = (string)$data['type2'];
        }

        if (array_key_exists('numDev2', $data)) {
            $this->numDev2 = (int)$data['numDev2'];
        }

        if (array_key_exists('devSignal2', $data)) {
            $this->devSignal2 = (int)$data['devSignal2'];
        }

        if (array_key_exists('billCash2', $data)) {
            $this->billCash2 = (int)$data['billCash2'];
        }

        if (array_key_exists('statusDev2', $data)) {
            $this->statusDev2 = (int)$data['statusDev2'];
        }

        if (array_key_exists('type3', $data)) {
            $this->type3 = (string)$data['type3'];
        }

        if (array_key_exists('colGel3', $data)) {
            $this->colGel3 = (int)$data['colGel3'];
        }

        if (array_key_exists('billCash3', $data)) {
            $this->billCash3 = (int)$data['billCash3'];
        }

        if (array_key_exists('statusDev3', $data)) {
            $this->statusDev3 = (int)$data['statusDev3'];
        }

        if (array_key_exists('type4', $data)) {
            $this->type4 = (int)$data['type4'];
        }

        if (array_key_exists('colCart4', $data)) {
            $this->colCart4 = (int)$data['colCart4'];
        }

        if (array_key_exists('billCash4', $data)) {
            $this->billCash4 = (int)$data['billCash4'];
        }

        if (array_key_exists('statusDev4', $data)) {
            $this->statusDev4 = (int)$data['statusDev4'];
        }


//        if (array_key_exists('type', $data) && is_array($data['type'])) {
//            foreach ($data['type'] as $custom_field) {
//                $this->type[] = new TypeWMDto($custom_field);
//            }
//        }
    }
}
