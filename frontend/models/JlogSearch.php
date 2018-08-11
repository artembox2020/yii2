<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Jlog;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;

/**
 * JlogSearch represents the model behind the search form of `frontend\models\Jlog`.
 */
class JlogSearch extends Jlog
{
    const PAGE_SIZE = 10;

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
        $entityHelper = new EntityHelper();
        $query = $entity->getUnitsQueryPertainCompany(new Jlog());
        $params = $entityHelper->makeParamsFromRequest(['type_packet', 'imei', 'address']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ]
        ]);

        $this->load($params);
        
        // grid filtering conditions
        if (!empty($params['type_packet'])) {
            $query->andFilterWhere([
                'type_packet' => $params['type_packet'],
            ]);
        }
        
        $query->andFilterWhere(['like', 'imei', $params['imei']]);
        
        $query->andFilterWhere(['like', 'address', $params['address']]);
        
        return $dataProvider;
    }
}
