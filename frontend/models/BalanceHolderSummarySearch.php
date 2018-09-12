<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\BalanceHolder;
use frontend\models\ImeiData;
use frontend\services\globals\Entity;

/**
 * BalanceHolderSummarySearch represents the model behind the search form of `frontend\models\BalanceHolder`.
 */
class BalanceHolderSummarySearch extends BalanceHolder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'created_at', 'deleted_at'], 'integer'],
            [['name', 'city', 'address', 'phone', 'contact_person', 'is_deleted'], 'safe'],
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
    public function baseSearch($params)
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new BalanceHolder());
        
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
    
    public function getSummaryByImei($imei, $timestampStart, $timestampEnd)
    {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new ImeiData());
        $query = $query->andWhere(['imei_id' => $imei]);
        $query = $query->andWhere([">=", 'created_at', $timestampStart]);
        $query = $query->andWhere(["<=", 'created_at', $timestampEnd]);
        $query->orderBy(['created_at' => SORT_ASC]);
        
        $summaryInfo = [];
        $prevDate = false;
        foreach ($query->all() as $imeiData)
        {
            
        }
    }
}
