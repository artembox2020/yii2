<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ImeiData;
use frontend\models\WmMashine;
use frontend\models\GdMashine;
use frontend\services\globals\Entity;

/**
 * ImeiDataSearch represents the model behind the search form of `frontend\models\ImeiData`.
 */
class ImeiDataSearch extends ImeiData
{
    const DEVICE_PAGE_SIZE = 5;

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
        $query = $entity->getUnitsQueryPertainCompany(new Imei());
        $query = $query->joinWith('imeiData', false, 'INNER JOIN');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => self::DEVICE_PAGE_SIZE ],
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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => self::DEVICE_PAGE_SIZE ],
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


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;                     
    }
}
