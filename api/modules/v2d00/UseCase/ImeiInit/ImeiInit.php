<?php

namespace api\modules\v2d00\UseCase\ImeiInit;

use api\modules\v2d00\UseCase\Command\Command;
use Exception;
use frontend\models\Imei;
use frontend\models\Jlog;
use frontend\services\custom\Debugger;
use frontend\services\parser\CParser;
use Yii;

class ImeiInit
{
    public function add($items)
    {
        $imei = ImeiInit::getImei($items->imei);
        $imei->imei_central_board = $items->imei;
        $imei->type_packet = $items->type;
        $imei->firmware_version = $items->pac->bootloader;
        $imei->firmware_version_cpu = empty($items->pac->firmware) ? null: $items->pac->firmware;
        $imei->firmware_6lowpan = $items->pac->radio;
        $imei->number_channel = $items->pac->channel;
        $imei->pcb_version = (string)$items->pac->PCB;
        $imei->phone_module_number = $items->pac->telephone;
        $imei->level_signal = $items->pac->rssi;
        $imei->on_modem_account = $items->pac->modemCash;
        $imei->traffic = empty($items->pac->traffic) ? null : $items->pac->traffic;
        $imei->ping = time() + Jlog::TYPE_TIME_OFFSET;

        if (!$imei->update(false)) {
            Yii::$app->response->statusCode = 500;
            throw new Exception('Imei not update!');
        }

        $this->addJlog($imei);

        Yii::$app->response->statusCode = 201;

        return Command::getCommand($imei->imei);
    }

    /**
     * @param int $imei
     * @return Imei
     * @throws Exception
     */
    public static function getImei(int $imei): Imei
    {
        if (!Imei::find()
                ->where(['imei' => $imei])
                ->andWhere(['status' => Imei::STATUS_ACTIVE])
                ->one()) {
            throw new Exception('Imei not exists!');
        }

        return Imei::findOne(['imei' => $imei]);
    }

    /**
     * Adds item to `j_log` table
     * 
     * @param Imei $imei
     */
    public function addJlog($imei)
    {
        $jlog = new Jlog();
        $parser = new CParser();
        $p = $parser->getInitPacket($imei);
        $jlog->createLogFromImei($imei, $p, Jlog::TYPE_PACKET_INITIALIZATION);
    }
}
