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
 * JlogDataCpSearch represents the model behind the search form of `frontend\models\JlogSearch`.
 */
class JLogDataCpSearch extends JlogSearch
{
    public $in_banknotes;
    public $money_in_banknotes;
    public $fireproof_residue;
    public $cp_status;
    public $evt_bill_acceptance;
    public $date_end;

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchData($params)
    {
        $entity = new Entity();
        $entityHelper = new EntityHelper();
        $query = $entity->getUnitsQueryPertainCompany(new Jlog());
        $query = $this->applyBetweenDateCondition($query);

        $query = $query->andFilterWhere(['type_packet' => self::TYPE_PACKET_DATA]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ],
            'sort' => [
                'attributes' => [
                    'date' => [
                        'default' => SORT_DESC
                    ],
                    'address' => [
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
     * Gets the real CP Status value from packet 'p'
     * 
     * @param string $p
     * @return integer|bool
     */
    public function getCPStatusFromDataPacket($p)
    {
        $cParser = new CParser();
        $packetData = $cParser->getImeiData($p);
        list($imeiData, $packet) = [$packetData['imeiData'], $packetData['packet']];

        $cpStatus = $cParser->getCPStatusFromPacketField($packet);

        if ($cpStatus) {

            return $cpStatus;
        }

        return $cParser->getEvtBillValidatorFromDataPacket($p, true);
    }

    /**
     * Gets the real EvtBill Validator value from packet 'p'
     * 
     * @param string $p
     * @return integer|bool
     */
    public function getEvtBillValidatorFromDataPacket($p)
    {
        $cParser = new CParser();
        $packetData = $cParser->getImeiData($p);
        $packet = $packetData['packet'];

        if (
            !($cParser->getCPStatusFromPacketField($packet))
            ||
            !($evtBillValidator = $cParser->getEvtBillValidatorFromDataPacket($p))
        ) {

            return false;
        }

        return $evtBillValidator;
    }

    /**
     * Gets the necessary param from packet 'p' 
     * 
     * @param string $p
     * @param string $param
     * @return integer|bool
     */
    public function getParamFromDataPacket($p, $param)
    {
        $cParser = new CParser();
        $packetData = $cParser->dParse($p);

        if (isset($packetData[$param])) {

            return $packetData[$param];
        }

        return  false;
    }

    /**
     * Gets date start view 
     * 
     * @param JLogDataCpSearch $model
     * @return string
     */
    public function getDateStartView($model)
    {
        $jlogInitSearch = new JlogInitSearch();

        return $jlogInitSearch->getDateView($model);
    }

    /**
     * Gets date end view 
     * 
     * @param JLogDataCpSearch $model
     * @return string
     */
    public function getDateEndView($model)
    {
        $jlogInitSearch = new JlogInitSearch();
        $model->date = isset($model->date_end) ? $model->date_end : $model->date;

        return $jlogInitSearch->getDateView($model);
    }

    /**
     * Gets address view 
     * 
     * @param JLogDataCpSearch $model
     * @return string
     */
    public function getAddressView($model)
    {
        $jlogInitSearch = new JlogInitSearch();

        return $jlogInitSearch->getAddressView($model);
    }
}
