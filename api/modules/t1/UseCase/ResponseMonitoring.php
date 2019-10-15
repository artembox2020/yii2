<?php

namespace api\modules\t1\UseCase;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\WmMashine;
use frontend\services\custom\Debugger;
use Yii;
use yii\httpclient\Client;

class ResponseMonitoring
{
    protected $wash_machine;
    protected $imei;

    public function __construct($wash_machine, $imei)
    {
        $array = [];
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

//        Debugger::dd($this->getIsActive($address->name)['time']);
        if ($this->getIsActive($address->name)
            and $this->getIsActive($address->name)['time'] > 10) {
            Yii::$app->db->createCommand('UPDATE t_bot_monitor SET time=:time WHERE is_active=:is_active AND address=:address')
                ->bindValue(':time', $wm_machine->display)
                ->bindValue(':address', $address->name)
                ->bindValue(':is_active', true)
                ->execute();
        }

        if ($this->getIsActive($address->name)
            and $this->getIsActive($address->name)['time'] <= 10
            and $this->getIsActive($address->name)['time'] > 0) {
            Yii::$app->db->createCommand(
                'UPDATE t_bot_monitor SET time=:time WHERE is_active=:is_active AND address=:address')
                ->bindValue(':time', $wm_machine->display)
                ->bindValue(':address', $address->name)
                ->bindValue(':is_active', true)
                ->execute();

            $status = new Monitoring();
            foreach ($this->getIsActiveAll($address->name) as $value) {
                $array['chat_id_and_key'][$value['chat_id']] = $value['key'];
                $array['num_w'] = $value['num_w'];
                $array['status_w'] = $status->getStatusW($wm_machine->current_status);
                $array['time'] = $value['time'];
            }
//            Debugger::dd($array);
            $this->getPush($array);
        }

        $status = new Monitoring();
//        Debugger::dd($status->getStatusW($wm_machine->current_status));

        if ($this->getIsActive($address->name)
            and $status->getStatusW($wm_machine->current_status) == 2
            or $this->getIsActive($address->name)['time'] == 'En') {

            $status = new Monitoring();
            foreach ($this->getIsActiveAll($address->name) as $value) {
                $array['chat_id_and_key'][$value['chat_id']] = $value['key'];
                $array['num_w'] = $value['num_w'];
                $array['status_w'] = $status->getStatusW($wm_machine->current_status);
                $array['time'] = $value['time'];
            }

            Yii::$app->db->createCommand()
                ->update('t_bot_monitor',
                    [ 'time' => $wm_machine->display, 'is_active' => false], //columns and values
                    [ 'is_active'=>true, 'address'=>$address->name ]) //condition, similar to where()
                ->execute();
            
            Yii::$app->db->createCommand()
                ->update('t_bot_monitor',
                    [ 'is_active' => false], //columns and values
                    [ 'is_active'=>true, 'address'=>$address->name ]) //condition, similar to where()
                ->execute();

//            Debugger::dd($array);
            $this->getPush($array);
        }


    }

    public function getIsActive($address)
    {
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('t_bot_monitor')
            ->where(['address' => $address])
            ->andWhere(['is_active' => true])
            ->one();

        return $rows;
    }

    public function getIsActiveAll($address)
    {
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('t_bot_monitor')
            ->where(['address' => $address])
            ->andWhere(['is_active' => true])
            ->all();

        return $rows;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getPush($array)
    {

        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl('http://bot.postirayka.com:5001/api/monitoring/notifyUsers')
            ->setData($array)
            ->send();

//        $client = new Client(['baseUrl' => 'http://bot.postirayka.com:5001/api/monitoring/notifyUsers']);
//        $response = $client->createRequest()
//            ->setFormat(Client::FORMAT_JSON)
//            ->setData($array)
//            ->send();

//        Debugger::dd($response);
//        if ($response->isOk) {
//            Debugger::dd($response->data);
//        }
    }
}
