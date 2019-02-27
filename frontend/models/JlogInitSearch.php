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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchInit($params)
    {
        $entity = new Entity();
        $entityHelper = new EntityHelper();
        $query = $entity->getUnitsQueryPertainCompany(new Jlog());

        $query = $this->applyBetweenDateCondition($query);

        $query = $query->andFilterWhere(['like', 'packet', $this->mashineNumber]);

        $query = $query->andFilterWhere(['type_packet' => self::TYPE_PACKET_INITIALIZATION]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['page_size'] ? $params['page_size'] : self::PAGE_SIZE
            ],
            'sort' => [
                'attributes' => [
                    'date' => [
                        'default' => SORT_DESC
                    ],
                    'address' => [
                        'default' => SORT_ASC
                    ],
                    'imei' => [
                        'default' => SORT_ASC
                    ],
                ]
            ]
        ]);

        $dataProvider->sort->attributes['date'] = [
            'asc' => ['STR_TO_DATE(j_log.date, \''.Imei::MYSQL_DATE_TIME_FORMAT.'\')' => SORT_ASC],
            'desc' => ['STR_TO_DATE(j_log.date, \''.Imei::MYSQL_DATE_TIME_FORMAT.'\')' => SORT_DESC],
        ];

        $this->load($params);

        // apply filters by address column

        $query = $this->applyFilterByValueMethod($query, 'address', $params);
        $query = $this->applyFilterByConditionMethod($query, 'address', $params, self::FILTER_CATEGORY_COMMON);

        // apply filters by date column 

        $query = $this->applyFilterByValueMethod($query, 'date', $params);
        $query = $this->applyFilterByConditionMethod($query, 'date', $params, self::FILTER_CATEGORY_DATE);

        $query->andFilterWhere(['like', 'imei', $params['imei']]);

        $query->andFilterWhere(['like', 'address', $params['address']]);

        return $dataProvider;
    }

    /**
     * Gets address view representation
     *
     * @param JlogInitSearch $model
     *
     * @return string
     */
    public function getAddressView($model)
    {
        $addressParts = explode(",", $model->address);
        $countParts = count($addressParts);

        if ($countParts >= 2) {
            $partOne = $addressParts[0];

            return $partOne." (".mb_substr($model->address, mb_strlen($partOne) + 1).")";
        }

        return $model->address;
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
