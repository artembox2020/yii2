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

        $timeFrom = 0;
        $timeTo = self::INFINITY;

        if (!empty($this->from_date)) {
            $this->from_date .= ' 00:00:00';
            $timeFrom = strtotime($this->from_date) - Jlog::TYPE_TIME_OFFSET;
        }

        if (!empty($this->to_date)) {
            $this->to_date .= ' 23:59:59';
            $timeTo = strtotime($this->to_date) - Jlog::TYPE_TIME_OFFSET;
        }

        $betweenCondition = new \yii\db\conditions\BetweenCondition(
            "UNIX_TIMESTAMP(STR_TO_DATE(date, '".Imei::MYSQL_DATE_TIME_FORMAT."'))", 
            'BETWEEN',
            $timeFrom,
            $timeTo
        );

        $query = $query->andWhere($betweenCondition);

        $query = $query->andFilterWhere(['like', 'packet', $this->mashineNumber]);

        $query = $query->andFilterWhere(['type_packet' => self::TYPE_PACKET_INITIALIZATION]);

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
}
