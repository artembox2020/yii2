<?php

namespace frontend\models;

use frontend\services\custom\Debugger;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ImeiData;
use frontend\models\WmMashine;
use frontend\models\GdMashine;
use frontend\services\globals\Entity;
use frontend\services\globals\QueryOptimizer;
use yii\helpers\ArrayHelper;
use frontend\controllers\MonitoringController;

/**
 * ImeiDataSearch represents the model behind the search form of `frontend\models\ImeiData`.
 */
class ImeiDataSearch extends ImeiData
{
    public $timestamp;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'imei_id', 'created_at', 'imei', 'level_signal', 'on_modem_account', 'in_banknotes', 'money_in_banknotes', 'fireproof_residue', 'price_regim', 'updated_at', 'deleted_at'], 'integer'],
            [['is_deleted'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param array $postParams
     * @return ActiveDataProvider
     */
    public function search($params, $postParams)
    {
        $entity = new Entity();
        $imeiIds = ImeiData::find()->select('imei_id')->distinct()->all();
        $imeiIds = ArrayHelper::getColumn($imeiIds, 'imei_id');

        $query = Imei::find()->andWhere(['imei.company_id' => $entity->getCompanyId(), 'imei.id' => $imeiIds]);

        $query = $query->innerJoin('address_balance_holder', 'address_balance_holder.id = imei.address_id')
                       ->andWhere(new \yii\db\conditions\OrCondition([
                           new \yii\db\conditions\AndCondition([
                              ['=', 'imei.status', Imei::STATUS_ACTIVE],
                              ['=', 'address_balance_holder.status', AddressBalanceHolder::STATUS_BUSY]
                           ]),
                           new \yii\db\conditions\AndCondition([
                              ['=', 'imei.status', Imei::STATUS_OFF],
                              ['=', 'address_balance_holder.status', AddressBalanceHolder::STATUS_FREE]
                           ])
                       ]))
                       ->andWhere(['address_balance_holder.is_deleted' => false]);

        $query = $this->makeOrder($query, $postParams);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $dataProvider;
    }

    /**
     * Adds order condition to query
     * 
     * @params \yii\db\Query $query
     * @params array $postParams
     * 
     * @return \yii\db\Query
     */
    public function makeOrder($query, $postParams)
    {
        if (empty($postParams['sortOrder'])) {
            $query = $query->orderBy(['address_balance_holder.address' => SORT_ASC]);

            return $query;
        }

        switch ($postParams['sortOrder']) {
            case MonitoringController::SORT_BY_ADDRESS:
                $query = $query->orderBy(['address_balance_holder.address' => SORT_ASC]);
                break;
            case MonitoringController::SORT_BY_SERIAL:
                $query = $query->orderBy(['address_balance_holder.serial_column' => SORT_ASC]);
                break;
            case MonitoringController::SORT_BY_BALANCEHOLDER:
                $query = $query->innerJoin('balance_holder', 'address_balance_holder.balance_holder_id = balance_holder.id')
                               ->andWhere(['balance_holder.is_deleted' => false])
                               ->orderBy(['balance_holder.name' => SORT_ASC]);
                break;
            case MonitoringController::SORT_BY_ADDRESS_DESC:
                $query = $query->orderBy(['address_balance_holder.address' => SORT_DESC]);
                break;
            case MonitoringController::SORT_BY_SERIAL_DESC:
                $query = $query->orderBy(['address_balance_holder.serial_column' => SORT_DESC]);
                break;
            case MonitoringController::SORT_BY_BALANCEHOLDER_DESC:
                $query = $query->innerJoin('balance_holder', 'address_balance_holder.balance_holder_id = balance_holder.id')
                               ->andWhere(['balance_holder.is_deleted' => false])
                               ->orderBy(['balance_holder.name' => SORT_DESC]);
                break;    
            default:
                $query = $query->orderBy(['address_balance_holder.address' => SORT_ASC]);
                break;
        }

        return $query;
    }

    /**
     * Creates data provider instance with search WmMashines query applied
     * 
     * @param int $id
     * @return ActiveDataProvider
     */
    public function searchWmMashinesByImeiId($id)
    {
        $query = WmMashine::getMachinesQueryByImeiId($id);
        $query = $query->select('id, type_mashine, number_device, bill_cash, level_signal, current_status, display, ping');
        $query = $query->union($this->searchGdMashinesByImeiId($id)->query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $rawSql = $dataProvider->query->createCommand()->rawSql;
        $dataProvider->query->sql = $rawSql.' ORDER BY number_device ASC';

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search GdMashines query applied
     * 
     * @param int $id
     * @return ActiveDataProvider
     */
    public function searchGdMashinesByImeiId($id)
    {
        $query = GdMashine::getMachinesQueryByImeiId($id);
        $query = $query->select('id, type_mashine, serial_number as number_device, bill_cash, deleted_at as level_signal, current_status, created_at as display, updated_at as ping');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search imei data query applied
     * 
     * @param int $id
     * @return ActiveDataProvider
     */
    public function searchImeiCardDataByImeiId($id)
    {
        $query = self::find();
        $query = $query->andWhere(['imei_id' => $id]);
        $query = $query->orderBy(['date' => SORT_DESC, 'updated_at' => SORT_DESC])->limit(1);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false
        ]);

        return $dataProvider;                     
    }

    /**
     * Gets the list of addresses, mapped for AutoComplete 
     * 
     * @param ActiveDbQuery $dbQuery
     * @return array
     */
    public function getAddressesMapped($dbQuery)
    {
        $items = QueryOptimizer::getItemsByQuery($dbQuery);
        $addressesMapped = [];
        $counter = 1;
        foreach($items as $item) {

            if (!$item->fakeAddress) {
                continue;
            }

            $value = $item->fakeAddress->address;
            $value .= $item->fakeAddress->floor ? ', '.$item->fakeAddress->floor : '';
            $addressesMapped[] = (object)['id' => $item->fakeAddress->id, 'value' => $value];
        }

        return $addressesMapped;
    }

    /**
     * Gets the list of imeis, mapped for AutoComplete 
     * 
     * @param ActiveDbQuery $dbQuery 
     * @return array
     */
    public function getImeisMapped($dbQuery)
    {
        $items = QueryOptimizer::getItemsByQuery($dbQuery);
        $imeisMapped = [];
        foreach($items as $item) {

            if (!$item->imei) {
                continue;
            }

            $value = $item->imei;
            $imeisMapped[] = (object)['id' => $item->id, 'value' => $value]; 
        }

        return $imeisMapped;
    }

    /**
     * Gets the list of all addresses, mapped for AutoComplete 
     * 
     * @param ActiveDbQuery $dbQuery 
     * @return array
     */
    public function getAllAddressesMapped($query)
    {
        $items = $query->all();
        $addressesMapped = [];
        foreach($items as $item) {

            if (!$item->address) {
                continue;
            }

            $value = $item->address.(!empty($item->floor) ? ', '.$item->floor : '');
            $addressesMapped[] = (object)['id' => $item->id, 'value' => $value]; 
        }

        return $addressesMapped;
    }

    /**
     * Assigns serial number to certain address 
     * 
     * @param array $params
     */
    public function setSerialNumber($params)
    {
        if (!empty($params['addressId']) && !empty($params['serialNumber'])) {
            $address = AddressBalanceHolder::findOne($params['addressId']);
            if ($address) {
                $address->serial_column = $params['serialNumber'];
                $address->save(false);
            }
        }
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\web\NotFoundHttpException
     */
    public function getImeiData($params)
    {
        $timestampBefore = time() + Jlog::TYPE_TIME_OFFSET;
        $entity = new Entity();
        $id = $entity->getUnitsPertainCompany(new Imei());

        $query = ImeiData::find()->andWhere(['imei_id' => $id])
            ->andWhere(['money_in_banknotes' => 0])
            ->andWhere(['<', 'created_at', $timestampBefore])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(1);
        $item = $query->one();

        if ($item) {
            $resultQuery = ImeiData::find()->andWhere(['imei_id' => $id])
                ->andWhere(['<', 'created_at', $item->created_at])
                ->andWhere(['!=', 'money_in_banknotes', 0])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(1);
            $resultItem = $resultQuery->one();

            $dataProvider = new ActiveDataProvider([
                'query' => $resultItem,
            ]);

            $this->load($params);

            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }

            return $dataProvider;

        }
    }
}
