<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Jlog;
use frontend\models\WmMashine;
use frontend\models\Imei;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use frontend\services\parser\CParser;
use yii\helpers\Html;

/**
 * JlogInitSearch represents the model behind the search form of `frontend\models\JlogSearch`.
 */
class JlogInitSearch extends JlogSearch
{
    public $pcb_version;
    public $firmware_6lowpan;
    public $on_modem_acount_number;
    public $level_signal;
    public $firmware_version;
    public $firmware_version_cpu;
    public $number_channel;

    /**
     * Gets address view representation
     *
     * @param JlogInitSearch $model
     * @param int $typePacket
     *
     * @return string
     */
    public function getAddressView($model, $typePacket = self::TYPE_PACKET_INITIALIZATION)
    {
        $model->address = $this->findAddressByStatic($model->address, $typePacket);
        $addressParts = explode(",", $model->address);
        $countParts = count($addressParts);

        if ($countParts >= 2) {
            $partOne = trim($addressParts[0]);
            $partTwo = trim(mb_substr($model->address, mb_strlen($partOne) + 1));
            $address = AddressBalanceHolder::find()
                            ->andFilterWhere(['like', 'address', $partOne])
                            ->andWhere(['or', ['like', 'floor', $partTwo], ['floor' => null], ['floor' => '']])
                            ->limit(1)
                            ->one();

            $addressString = $partOne." (".$partTwo.")";
        } else {
            $addressString = $model->address;
            $address = AddressBalanceHolder::find()
                            ->andWhere(['like', 'address', $model->address])
                            ->limit(1)
                            ->one();
        }

        return Yii::$app->commonHelper->link($address, [], $addressString);
    }

    /**
     * Gets date view representation
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getDateView($model)
    {
        $dateParts = explode(' ', $model->date);

        return date('d.m.Y', strtotime($dateParts[0])).' '.$dateParts[1];
    }

    /**
     * Parses initialization packet data
     *
     * @param JlogInitSearch $model
     *
     * @return array
     */
    public function parseInitialization($model)
    {
        $packet = $model->packet;
        $cParser = new CParser();
        $packetData = $cParser->iParse($packet);

        return $packetData;
    }

    /**
     * Gets level signal from the packet data
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getLevelSignal($model)
    {
        $packetData = $this->parseInitialization($model);

        return $packetData['level_signal'];
    }

    /**
     * Gets on_modem_account and number from the packet data
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getOnModemAccountNumber($model)
    {
        $packetData = $this->parseInitialization($model);

        if (is_null($packetData['on_modem_account'])) {

            return null;
        }

        return $packetData['on_modem_account'].' - '.$packetData['phone_module_number'];
    }

    /**
     * Gets pcb version from the packet data
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getPcbVersion($model)
    {
        $packetData = $this->parseInitialization($model);

        return $packetData['pcb_version'];
    }

    /**
     * Gets firmware version cpu from the packet data
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getFirmwareVersionCpu($model)
    {
        $packetData = $this->parseInitialization($model);

        return $packetData['firmware_version_cpu'];
    }

    /**
     * Gets firmware_6lowpan from the packet data
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getFirmware6Lowpan($model)
    {
        $packetData = $this->parseInitialization($model);

        return $packetData['firmware_6lowpan'];
    }

    /**
     * Gets number_channel from the packet data
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getNumberChannel($model)
    {
        $packetData = $this->parseInitialization($model);

        return $packetData['number_channel'];
    }
}
