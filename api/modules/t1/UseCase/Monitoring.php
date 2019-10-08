<?php

namespace api\modules\t1\UseCase;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\WmMashine;
use frontend\services\custom\Debugger;
use Yii;

class Monitoring
{
    public function getStaff($items)
    {
//        Debugger::dd($items);
        $address = AddressBalanceHolder::find()
            ->andWhere(['name' => $items->address])
            ->one();

        $imei = Imei::find()
            ->andWhere(['address_id' => $address->id])
            ->andWhere(['imei.status' => Imei::STATUS_ACTIVE])
            ->limit(1)
            ->one();

        $wm_machine = WmMashine::find()
            ->andWhere(['imei_id' => $imei->id])
            ->andWhere(['number_device' => $items->wm])
            ->andWhere(['wm_mashine.status' => WmMashine::STATUS_ACTIVE])
            ->one();

        //////////////////////////

            if (!$this->getFreeWash($wm_machine->current_status)
            or !$this->getErrorWash($wm_machine->current_status)) {
                Yii::$app->db->createCommand()->insert('t_bot_monitor', [
                    'address' => $items->address,
                    'num_w' => $items->wm,
                    'chat_id' => $items->chat_id,
                    'key' => $items->key,
                    'status_w' => $wm_machine->current_status,
                    'time' => $this->getTime($wm_machine->display),
                    'is_active' => true,
                    'created_at' => date("Y-m-d H:i:s")
                ])->execute();

                $returnData = [
                    [
                        'chat_id' => $items->chat_id,
                        'num_w' => $items->wm,
                        'status_w' => $this->getStatusW($wm_machine->current_status),
                        'time' => $this->getTime($wm_machine->display),
                        'key' => $items->key
                    ]
                ];

            return $returnData;
            }

            $returnData = [
                [
                    'chat_id' => $items->chat_id,
                    'num_w' => $items->wm,
                    'status_w' => $this->getStatusW($wm_machine->current_status),
                    'time' => $this->getTime($wm_machine->display),
                    'key' => $items->key
                ]
            ];

            return $returnData;
    }

    public function getTime($time)
    {
        if ($time == '2H') { $time = 120; }

        return $time;
    }

    public function getStatusW($current_status)
    {
        if ($this->getFreeWash($current_status)) {
            $status = 2;

            return $status;
        }

        if ($this->getErrorWash($current_status)) {
            $status = 4;

            return $status;
        }

        $status = 1;

        return $status;
    }

    public function getFreeWash($status)
    {
        if ($status == 1
            or $status == 2
            or $status == 7
            or $status == 8) {
            $status = 2;

            return $status;
        }
    }

    public function getErrorWash($status)
    {
        if ($status >= 9) {
            $status = 4;

            return $status;
        }
    }
}
