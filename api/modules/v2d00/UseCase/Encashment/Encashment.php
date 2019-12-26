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

    // set maximum package delay period as 4 months
    const MAX_PACKAGE_DELAY_TIMESTAMP = 3600*24*30*4;

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
            $cbl->unix_time_offset = $this->getRealUnixTimeOffset($items->pac->time);
            $cbl->fireproof_counter_hrn = (float)$items->pac->totalCash;
            $cbl->collection_counter = (float)$items->pac->collection;
            $cbl->last_collection_counter = (float)$items->pac->collection_last;
            $cbl->notes_billiards_pcs = $items->pac->notes->number;
            $cbl->banknote_face_values = $this->getFormatNote($items->pac->notes);
            $cbl->amount_of_coins = $items->pac->coins->number;
            $cbl->coin_face_values = $this->getFormatCoins($items->pac->coins);
            $cbl->is_deleted = false;

            if ($this->checkImeiAndTimestampUniquity($cbl)) {
                $cbl->insert();
            }

            $imei->ping = time();
            $imei->save();
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 500;
            return $exception;
        }

        if (!Command::getCommand($imei->imei)) {
            Yii::$app->response->statusCode = 201;
        }

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

    /**
     * Gets package unix timestamp by given creation timestamp regarding possible package coming delay 
     * @param int $unixTimeOffset
     *
     * @return int
     */
    public function getRealUnixTimeOffset(int $unixTimeOffset): int
    {
        if (!empty($unixTimeOffset) && ($unixTimeOffset > (time() - self::MAX_PACKAGE_DELAY_TIMESTAMP))) {

            return $unixTimeOffset;
        }

        return time();
    }

    /**
     * Check whether data  index {imei_id, unix_time_offset} is unique by record item
     *
     * @param CbEncashment $cbl
     *
     * @return bool
     */
    public function checkImeiAndTimestampUniquity($cbl)
    {
        $count = $cbl::find()
        ->andWhere([
            'imei_id' => $cbl->imei_id,
            'unix_time_offset' => $cbl->unix_time_offset
        ])
        ->count();

        return !$count;
    }
}
