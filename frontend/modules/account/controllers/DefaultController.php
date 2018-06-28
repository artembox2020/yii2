<?php

namespace frontend\modules\account\controllers;

use backend\services\mail\MailSender;
use backend\models\Company;
use frontend\services\custom\Debugger;
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
 * Default controller.
 */
class DefaultController extends Controller
{
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
     * Settings User models.
     *
     * @return mixed
     */
    public function actionSettings()
    {
        $model = Yii::$app->user->identity->userProfile;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => Yii::$app->user->id]);
        } else {
            return $this->render('settings', ['model' => $model]);
        }
    }

    /**
     * @inheritdoc
     */
    public function actionPassword()
    {
        $model = new PasswordForm();
        $model->setUser(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'Your password has been successfully changed.'));

            return $this->refresh();
        } else {
            return $this->render('password', ['model' => $model]);
        }
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionUsers()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->sort = [
            'defaultOrder' => ['created_at' => SORT_DESC],
        ];

        return $this->render('users', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param int $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'profile' => UserProfile::findOne($id),
        ]);
    }

    /**
     * Displays message page.
     *
     * @return mixed
     */
    public function actionMessage($id)
    {
        $user = User::findOne($id);
        if ($user) {
            $model = new MessageForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail($user->email)) {
                    Yii::$app->session->setFlash('success', Yii::t('frontend', 'Your message has been sent successfully.'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('frontend', 'There was an error sending your message.'));
                }

                return $this->refresh();
            } else {
                return $this->render('message', [
                    'model' => $model,
                    'user' => $user,
                ]);
            }
        } else {
            throw new NotFoundHttpException(Yii::t('frontend', 'Page not found.'));
        }
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->isGuest) {
            $model = new UserForm();
            $model->setScenario('create');
            
            if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }
            

            if ($model->load(Yii::$app->request->post())) {
                $model->other = $model->password;
                $model->save();

                $manager = User::findOne(Yii::$app->user->id);
                $user = User::findParent()->where(['email' => $model->email])->one();

                $user->company_id = $manager->company_id;
                $user->save();
    
                if($model->status == User::STATUS_ACTIVE)
                {
                    // send invite mail
                    $password = $model->other;
                    $sendMail = new MailSender();
                    $company = Company::findOne(['id' => $manager->company_id]);
                    $user = User::findOne(['email' => $model->email]);
                    $sendMail->sendInviteToCompany($user, $company, $password);

                    Yii::$app->session->setFlash('success', Yii::t('backend', 'Send ' . $model->username . ' invite'));
                }
                
                return $this->redirect(['users']);
            }

            $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');

            unset($roles[array_search('administrator', $roles)]);
            unset($roles[array_search('manager', $roles)]);
            unset($roles[array_search('user', $roles)]);

            foreach ($roles as $key => $role) {
                $roles[$key] = Yii::t('backend', $role);
            }

            $model->status = 1;
            return $this->render('create', [
                'model' => $model,
                'roles' => $roles
            ]);

        }

        return $this->render ('/denied/access-denied', [
            $this->accessDenied()
        ]);
    }

    private function accessDenied()
    {
        return Yii::$app->session->setFlash(
            'error',
            Yii::t('frontend', 'Access denied')
        );
    }
}
