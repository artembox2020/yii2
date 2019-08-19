<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UserProfile;
use frontend\models\ImeiDataSearch;
use frontend\models\ImeiAction;
use frontend\models\WmMashineDataSearch;
use frontend\models\WmMashine;
use frontend\models\Jlog;
use frontend\models\CustomerCards;
use backend\models\search\TransactionSearch;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use backend\models\search\CardSearch;
use frontend\models\Transactions;
use frontend\services\globals\Entity;

/**
 * customer cards processing
 * Class MapController
 * @package frontend\controllers
 */
class MapController extends \frontend\controllers\Controller
{
    /**
     * Gets cards and users list
     * 
     * @return string
     */
    public function actionIndex()
    {
        $cards = new CardSearch();
        $entity = new Entity();
        
        $dataProvider = $cards->searchPertainCompany($entity->getCompanyId(), Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'cards' => $cards,
                'dataProvider' => $dataProvider,
                'action' => $this->action->id
            ]
        );
    }

    /**
     * Gets cards and users list
     * 
     * @return string
     */
    public function actionUser()
    {
        $cards = new CardSearch();
        $entity = new Entity();
        $dataProvider = $cards->searchUserPertainCompany($entity->getCompanyId(), Yii::$app->request->queryParams);

        return $this->render(
            'user',
            [
                'cards' => $cards,
                'dataProvider' => $dataProvider,
                'action' => $this->action->id,
                'transaction' => new Transactions()
            ]
        );
    }

    /**
     * Card of card action
     * 
     * @param int $cardNo
     * 
     * @return string
     */
    public function actionCardofcard($cardNo)
    {
        $transactionSearch = new TransactionSearch();
        $dataProvider = $transactionSearch-> search($cardNo, Yii::$app->request->queryParams);

        return $this->render(
            'cardofcard',
            [
                'transactionSearch' => $transactionSearch,
                'dataProvider' => $dataProvider,
                'card' => CustomerCards::find()->andWhere(['card_no' => $cardNo])->limit(1)->one(),
                'userProfile' => new UserProfile(),
                'transaction' => new Transactions(),
                'action' => $this->action->id
            ]
        );
    }

    /**
     * Gets card of the user action
     * 
     * @param int $userId
     * 
     * @return string
     */
    public function actionUserscard($userId)
    {
        $cards = new CardSearch();
        $transactionSearch = new TransactionSearch();
        $dataProvider = $cards->searchByUserId($userId, Yii::$app->request->queryParams);
        $transactionDataProvider = $transactionSearch->search(
            $cards->findCardsByUserId($userId), Yii::$app->request->queryParams
        );

        return $this->render(
            'userscard',
            [
                'cards' => $cards,
                'dataProvider' => $dataProvider,
                'userId' => $userId,
                'transactionDataProvider' => $transactionDataProvider,
                'transactionSearch' => $transactionSearch,
                'action' => $this->action->id,
                'user' => User::findOne($userId),
                'cards' => new CardSearch()
            ]
        );
    }
}
