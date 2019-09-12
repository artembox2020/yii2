<?php

namespace frontend\modules\account\controllers;

use backend\services\mail\MailSender;
use frontend\models\Company;
use frontend\services\custom\Debugger;
use frontend\services\logger\src\service\LoggerService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;
use common\models\UserProfile;
use frontend\modules\account\models\MessageForm;
use frontend\modules\account\models\PasswordForm;
use frontend\modules\account\models\search\UserSearch;
use backend\models\UserForm;

/**
 * Class CustomerController
 * @package frontend\modules\account\controllers
 */
class CustomerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->layout = 'customer';

        return parent::beforeAction($action);
    }

    /**
     * Check whether user has privilleges
     * 
     * @param int $id
     * 
     * @return bool
     */
    private function canUser($id = false)
    {
        if (Yii::$app->user->can(User::ROLE_SUPER_ADMINISTRATOR, ['class'=>static::class])) {
            
            return true;
        }

        if (!Yii::$app->user->can(User::ROLE_CUSTOMER, ['class'=>static::class])) {

            return false;
        }

        if (!empty($id)) {

            return Yii::$app->user->id == $id ? true : false;
        }

        return true;
    }

    /**
     * Renders access denied page
     * 
     * @return string
     */
    private function renderAccessDenied()
    {
        \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');

        return $this->render('@app/modules/account/views/denied/access-denied');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($id)
    {
        if (!$this->canUser($id)) {

            return $this->renderAccessDenied();
        }

        $profile = UserProfile::findOne($id);

        Yii::$app->controllerNamespace = 'frontend\controllers';

        return $this->redirect(['/map/userscard', 'userId' => $profile->user_id]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {

            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('frontend', 'Page not found.'));
        }
    }

    /**
     * User Profile page
     * 
     * @return string
     */
    public function actionUserProfile()
    {

        return $this->render('user-profile');
    }
}
