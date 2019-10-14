<?php

namespace api\modules\t1\UseCase;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\WmMashine;
use frontend\services\custom\Debugger;
use Yii;
use Yii\httpclient\Client;

class ResponseMonitoring
{
    protected $wash_machine;
    protected $imei;

    public function __construct($wash_machine, $imei)
    {
        $this->wash_machine = $wash_machine;
        $this->imei = $imei;

        $imei = Imei::find()
            ->andWhere(['imei' => $imei->imei])
            ->andWhere(['imei.status' => Imei::STATUS_ACTIVE])
            ->limit(1)
            ->one();

        $address = AddressBalanceHolder::find()
            ->andWhere(['id' => $imei->address_id])
            ->one();

        $wm_machine = WmMashine::find()
            ->andWhere(['imei_id' => $imei->id])
            ->andWhere(['number_device' => $this->getIsActive($address->name)['num_w']])
            ->andWhere(['wm_mashine.status' => WmMashine::STATUS_ACTIVE])
            ->one();

//        Debugger::dd($wm_machine->display);
        if ($this->getIsActive($address->name)
            and $this->getIsActive($address->name)['time'] > 10) {
            Yii::$app->db->createCommand('UPDATE t_bot_monitor SET time=:time WHERE address=:address')
                ->bindValue(':time', $wm_machine->display)
                ->bindValue(':address', $address->name)
                ->execute();
        }

        if ($this->getIsActive($address->name)
            and $this->getIsActive($address->name)['time'] <= 10) {
            Yii::$app->db->createCommand(
                'UPDATE t_bot_monitor SET time=:time WHERE address=:address')
                ->bindValue(':time', $wm_machine->display)
                ->bindValue(':address', $address->name)
                ->execute();

            $this->getPush();
        }


    }

    public function getIsActive($address)
    {
        $rows = (new \yii\db\Query())
            ->select(['is_active', 'time', 'num_w'])
            ->from('t_bot_monitor')
            ->where(['address' => $address])
            ->andWhere(['is_active' => true])
            ->one();

        return $rows;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getPush()
    {
        $client = new Client(['baseUrl' => 'http://bot.postirayka.com:5001/api/monitoring/notifyUsers']);
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setData([
                'chat_id_and_key' => [1,2,3],
                'num_w' => 1,
                'status_w' => 1,
                'time' => 10,
            ])
            ->send();
        if ($response->isOk) {
            Debugger::dd($response->data);
        }
    }
}
