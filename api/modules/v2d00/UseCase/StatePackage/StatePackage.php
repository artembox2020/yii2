<?php

namespace api\modules\v2d00\UseCase\StatePackage;

use api\modules\v2d00\UseCase\ImeiInit\ImeiInit;
use Exception;
use frontend\models\Imei;
use frontend\models\ImeiData;
use frontend\models\Jlog;
use Yii;

class StatePackage
{
    public function create($items)
    {
        try {
            $this->addImei($items);
            $this->addWm($items);
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 500;
            return $exception;
        }

        Yii::$app->response->statusCode = 201;
        return 'SP create!';
    }

    public function addImei($items)
    {
//        $imei = ImeiInit::getImei($items->imei);
//        $imeiData = new ImeiData();
//        $imeiData->imei_id = $imei->id;
//        $imeiData->created_at = time() + Jlog::TYPE_TIME_OFFSET;
//        $imeiData->imei = $items->imei;
//        $imeiData->fireproof_residue = $items->pac->totalСash;
//        $imeiData->in_banknotes = $items->pac->numberNotes;
//        $imeiData->on_modem_account = $items->pac->collection;
//        $imeiData->cb_version = $imei->firmware_version;
//        $imeiData->evt_bill_validator = $items->pac->workStatus->validState;
//        $imei->status = $items->pac->workStatus->CenBoard;
        return 'Ok';
    }

    public function addWm($items)
    {
        $imei = ImeiInit::getImei($items->imei);
        return 'Ok';
    }
}
