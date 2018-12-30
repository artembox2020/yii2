<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class StorageHistorySearch
 * @package frontend\models
 */
class StorageHistorySearch extends StorageHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'imei_id', 'number_device', 'status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type', 'is_deleted'], 'safe'],
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

    public function search($params)
    {
        $user = User::findOne(Yii::$app->user->id);
//        $query = WmMashine::find()
//            ->where(['status' => WmMashine::STATUS_OFF])
//            ->orWhere(['status' => WmMashine::STATUS_JUNK])
//            ->orWhere(['status' => WmMashine::STATUS_UNDER_REPAIR])
//            ->andWhere(['company_id' => $user->company_id])
//            ->all();

        $query = StorageHistory::find()
            ->where(['company_id' => $user->company_id])->orderBy('created_at DESC')
            ->andWhere(['storage_history.is_deleted' => false]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        $query->andFilterWhere(['like', 'type', $this->type])
//            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
