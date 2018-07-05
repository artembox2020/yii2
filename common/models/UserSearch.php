<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\conditions\OrCondition;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $name; // field for filtering by firstname and lastname
    public $position; // field for filtering by position
    
    /** @var int PAGE_SIZE */
    const PAGE_SIZE = 10;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'created_at', 'updated_at', 'action_at', 'deleted_at', 'is_deleted'], 'integer'],
            [['username', 'auth_key', 'access_token', 'password_hash', 'email', 'ip', 'other', 'status'], 'safe'],
            [['name','position'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function searchEmployees($params)
    {
        $user = User::findOne(Yii::$app->user->id);
        
        $query = User::find()
                ->andWhere(['company_id' => $user->company_id])  // user belongs to company
                ->andWhere(['!=', 'id',Yii::$app->user->id]);    // exclude company manager
                //->andWhere(['is_deleted' => false, 'status' => User::STATUS_ACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => UserSearch::PAGE_SIZE]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status
        ]);
        
        $query->joinWith(['userProfile' => function ($q) {
            if(!empty($this->name)) {
                $q->andWhere(new OrCondition([
                    ['like','user_profile.firstname', $this->name],
                    ['like','user_profile.lastname', $this->name],
                ]));
            }
            
            if(!empty($this->position)) {
                $q->andWhere(
                    ['like','user_profile.position', $this->position]
                );
            }
        }]);
        
        return $dataProvider;
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
        $user = User::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'action_at' => $this->action_at,
            'deleted_at' => $this->deleted_at,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'other', $this->other]);
        
        return $dataProvider;
    }
    
}
