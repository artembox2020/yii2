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
use frontend\models\UserBlacklist;
use backend\models\search\TransactionSearch;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use backend\models\search\CardSearch;
use frontend\models\Transactions;
use backend\models\UserForm;
use frontend\services\globals\Entity;

/**
 * Cards management: cards and card users info + card operations (block/unblock, refill)
 * Class MapController
 * @package frontend\controllers
 */
class MapController extends \frontend\controllers\Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->can('customer')) {
            $this->layout = '@frontend/modules/account/views/layouts/customer';
        }

        return parent::beforeAction($action);
    }

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
        if ( !\Yii::$app->user->can('editCustomer', ['class'=>static::class]) && Yii::$app->user->id != $userId) {
            \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');

            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $cards = new CardSearch();
        $transactionSearch = new TransactionSearch();
        $entity = new Entity();
        $companyId = $entity->getCompanyId();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $cards->searchByUserId($userId, $params);
        $transactionDataProvider = $transactionSearch->search(
            $cards->findCardsByUserId($userId), $params, true
        );

        // card operations
        if (!empty($post) || ($post = Yii::$app->request->post())) {
            $card = $this->getModel(['card_no' => $post['card_no']], new CustomerCards());

            if ($result = $this->updateMapData($post, $card)) {

                return $result;
            }
        }

        $userForm = new UserForm();
        $userForm->setModel(User::findOne($userId));
        $profile = UserProfile::findOne($userId);

        //$render = $renderPartial ? 'renderPartial' : 'render';

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
                'cards' => new CardSearch(),
                'userForm' => $userForm,
                'profile' => $profile,
                'roles' => $this->getRoles(),
                'companyId' => $companyId,
                'authorId' => Yii::$app->user->id
            ]
        );
    }

    /**
     * Blocks/unblocks user depending on it's status by id
     * 
     * @param int $userId
     * @param string $comment
     */
    public function actionBlockUnblockUser($userId, $comment)
    {
        if ( !\Yii::$app->user->can('editCustomer', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');

            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $userBlacklist = new UserBlacklist();
        $entity = new Entity();
        $companyId = $entity->getCompanyId();
        $authorId = Yii::$app->user->id;
        $userBlacklist->reverseUser($userId, $authorId, $companyId, $comment);

        Yii::$app->session->set(
            'update-map-data-status',
            Yii::$app->mapBuilder->getFlashMessageByStatus(Yii::$app->mapBuilder::STATUS_SUCCESS)
        );
    }

    /**
     * Assigns card to a user 
     * 
     * @param int $userId
     * @param int $cardNo
     * 
     * @return string
     */
    public function actionCardConfirm($userId, $cardNo)
    {
        if ( !\Yii::$app->user->can('editCustomer', ['class'=>static::class]) && Yii::$app->user->id != $userId) {
            \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');

            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $card = new CustomerCards();

        if ($card->checkCardAvailable($userId, $cardNo)) {
            $card->assignCard($userId, $cardNo);

            return json_encode(['status' => Yii::$app->mapBuilder::STATUS_SUCCESS]);
        }

        return json_encode(['status' => Yii::$app->mapBuilder::STATUS_ERROR]);
    }
}
