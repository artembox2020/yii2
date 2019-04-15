<?php

namespace frontend\modules\forward\service;

use Codeception\Util\Debug;
use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use frontend\models\ImeiData;
use frontend\models\WmMashine;
use frontend\modules\forward\interfaces\ServiceForwardInterface;
use frontend\services\custom\Debugger;
use Yii;

/**
 * Class ServiceForward
 * @package frontend\modules\forward\service
 */
class ServiceForward implements ServiceForwardInterface
{
    public $array = array();
    public $result = array();

    /**
     * getStaff принимает строку (короткий адрес) возвращает связанные объекты в сформированном
     * массиве
     * array(2) {
    ["param"]=>array(2) {
     ["central_board_status"]=>string(2) "OK"
     ["bill_acceptor_status"]=>string(2) "OK"
    }
    ["ТЕСТ САЙТА ДЛЯ СТУДЕНТА 1"]=>array(6) {
     [1]=>array(3) {
        ["device_number"]=>float(1)
        ["display"]=>string(0) ""
        ["status"]=>string(20) "Відключено"}
    [2]=>array(3) {
        ["device_number"]=>float(2)
        ["display"]=>string(0) ""
        ["status"]=>string(20) "Відключено"
    ...
     * @param string $address_name
     * @return array
     */
    public function getStaff(string $address_name): array
    {
        $address = AddressBalanceHolder::find()
            ->andWhere(['name' => $address_name])
            ->one();

        $imei = Imei::find()
            ->andWhere(['address_id' => $address->id])
            ->andWhere(['imei.status' => Imei::STATUS_ACTIVE])
            ->one();

        $wm_machine = WmMashine::find()
            ->andWhere(['imei_id' => $imei->id])
            ->andWhere(['wm_mashine.status' => WmMashine::STATUS_ACTIVE])
            ->all();

        $imeiData = ImeiData::find()
            ->andWhere(['imei_id' => $imei->id])
            ->orderBy('created_at DESC')
//            ->limit(1);
            ->one();
//        ->limit(1);

//        Debugger::dd($imeiData);

        $this->result['param'] = [
            'central_board_status' => Yii::t('imeiData', $imeiData->status_central_board[$imeiData->packet]),
            'bill_acceptor_status' => Yii::t('imeiData', $imeiData->status_bill_acceptor[$imeiData->evt_bill_validator])
        ];

        foreach ($wm_machine as $key => $value) {
            $this->array[$value->number_device] = [
                'device_number' => $value->number_device,
                'display' => $value->display,
                'status' => Yii::t('frontend', $value->current_state[$value->current_status])];
        }

        $this->result[$address->address . ' ' . $address->floor] = $this->array;

        return $this->result;
    }
}
