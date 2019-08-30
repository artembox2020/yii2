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
 * Cards management: cards and card users info + card operations (block/unblock, refill)
 * Class MapController
 * @package frontend\controllers
 */
class MapController extends \frontend\controllers\Controller
{
    /**
     * Cards index list
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
     * Users index list 
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
     * Card info by card no + card operations (block/unblock, refill)
     * 
     * @param int $cardNo
     * 
     * @return string
     */
    public function actionCardofcard($cardNo)
    {
        $card = $this->getModel(['card_no' => $cardNo], new CustomerCards());
        $transactionSearch = new TransactionSearch();
        $dataProvider = $transactionSearch->search($cardNo, Yii::$app->request->queryParams);

        // card operations
        if (($post = Yii::$app->request->post()) && ($result = $this->updateMapData($post, $card))) {

            return $result;
        }

        return $this->render(
            'cardofcard',
            [
                'transactionSearch' => $transactionSearch,
                'dataProvider' => $dataProvider,
                'card' => $card,
                'userProfile' => new UserProfile(),
                'transaction' => new Transactions(),
                'action' => $this->action->id,
            ]
        );
    }

    /**
     * User cards info by user id + card operations (block/unblock, refill)
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

        // card operations
        if ($post = Yii::$app->request->post()) {
            $card = $this->getModel(['card_no' => $post['card_no']], new CustomerCards());

            if ($result = $this->updateMapData($post, $card)) {

                return $result;
            }
        }

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

    /**
     * Performs card operations by card post data (block/unblock + refill)
     
     * @param array $post
     * @param \frontend\models\CustomerCards $card
     * 
     * @return string|bool
     */
    public function updateMapData($post, $card)
    {
        $model = Yii::$app->mapBuilder->getUpdateMapDataModelFromPost($post, $card);

        Yii::$app->session->set(
            'update-map-data-status',
            Yii::$app->mapBuilder->getFlashMessageByStatus(
                $model ? $model->status : Yii::$app->mapBuilder::STATUS_ERROR
            )
        );

        // in case of need payment confirmation redirect to liqpay payment page
        if ($model->status == Yii::$app->mapBuilder::STATUS_PENDING_CONFIRMATION) {

            return $this->render(
                'confirm_payment',
                [
                    'payment_button' => Yii::$app->mapBuilder->createOrderAndPaymentButton(
                        $model, env('SERVER_URL'), Yii::$app->homeUrl.Yii::$app->request->url
                    )
                ]
            );
        }

        return false;
    }
}
