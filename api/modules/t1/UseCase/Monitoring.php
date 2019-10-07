<?php

namespace api\modules\t1\UseCase;

use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\WmMashine;
use frontend\services\custom\Debugger;
use Yii;

class Monitoring
{
    /**
     * @param string $address_name
     * @throws \yii\db\Exception
     */
    public function getStaff(string $address_name, $wm_num): void
    {
        $address = AddressBalanceHolder::find()
            ->andWhere(['name' => $address_name])
            ->one();

        $imei = Imei::find()
            ->andWhere(['address_id' => $address->id])
            ->andWhere(['imei.status' => Imei::STATUS_ACTIVE])
            ->limit(1)
            ->one();

        $wm_machine = WmMashine::find()
            ->andWhere(['imei_id' => $imei->id])
            ->andWhere(['number_device' => $wm_num])
            ->andWhere(['wm_mashine.status' => WmMashine::STATUS_ACTIVE])
            ->one();

        $res = Yii::$app->db->createCommand('SELECT *
          FROM imei_data WHERE imei_id = :imei_id ORDER BY created_at DESC LIMIT 1')
            ->bindValue(':imei_id', $imei->id)
            ->queryOne();

        Debugger::dd($wm_machine->display);


    }
}
