<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ImeiData;
use frontend\models\WmMashine;
use frontend\models\GdMashine;
use frontend\services\globals\Entity;
use yii\helpers\ArrayHelper;

/**
 * ImeiDataSearch represents the model behind the search form of `frontend\models\ImeiData`.
 */
class ImeiDataSearch extends ImeiData
{
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
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
                       ]));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $dataProvider;
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
            'sort' => false
        ]);

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
}
