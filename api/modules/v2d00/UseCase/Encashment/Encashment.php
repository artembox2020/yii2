<?php

namespace api\modules\v2d00\UseCase\Encashment;

use api\modules\v2d00\UseCase\ImeiInit\ImeiInit;
use api\modules\v2d00\UseCase\Command\Command;
use frontend\models\CbEncashment;
use frontend\models\CbLogSearch;
use frontend\services\custom\Debugger;
use Yii;

/**
 * Class Encashment
 * @package api\modules\v2d00\UseCase\Encashment
 */
class Encashment
{
    const CB = 'cb';

    /**
     * @param $items
     * @return \Exception|string
     * @throws \Throwable
     */
    public function add($items)
    {
        try {
            $imei = ImeiInit::getImei($items->imei);
            $cbl = new CbEncashment();
            $cbl->company_id = $imei->company_id;
            $cbl->address_id = $imei->address_id;
            $cbl->imei_id = $imei->id;
            $cbl->imei = $items->imei;
            $cbl->device = self::CB;
            $cbl->status = CbLogSearch::TYPE_ENCASHMENT_STATUS;
            $cbl->unix_time_offset = $items->pac->time;
            $cbl->fireproof_counter_hrn = (float)$items->pac->totalСash;
            $cbl->collection_counter = (float)$items->pac->collection;
            $cbl->last_collection_counter = (float)$items->pac->collection_last;
            $cbl->notes_billiards_pcs = $items->pac->notes->number;
            $cbl->banknote_face_values = $this->getFormatNote($items->pac->notes);
            $cbl->amount_of_coins = $items->pac->coins->number;
            $cbl->coin_face_values = $this->getFormatCoins($items->pac->coins);
            $cbl->is_deleted = false;
            $cbl->insert();
            $imei->ping = time();
            $imei->save();
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 500;
            return $exception;
        }

        Yii::$app->response->statusCode = 201;
        return Command::getCommand($imei->imei);
    }

    /**
     * @param $objectNotes
     * @return string
     */
    public function getFormatNote($objectNotes)
    {
        return '1-' . $objectNotes->one .
                '+' .
                '2-' . $objectNotes->two .
                '+' .
                '5-' . $objectNotes->five .
                '+' .
                '10-' . $objectNotes->ten .
                '+' .
                '20-' . $objectNotes->twenty .
                '+' .
                '50-' . $objectNotes->fifty;
    }

    /**
     * @param $objectCoins
     * @return string
     */
    public function getFormatCoins($objectCoins)
    {
        return '1-' . $objectCoins->one .
            '+' .
            '2-' . $objectCoins->two .
            '+' .
            '5-' . $objectCoins->five .
            '+' .
            '10-' . $objectCoins->ten;
    }
}
