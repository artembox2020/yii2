<?php

namespace frontend\controllers;

use common\models\UserProfile;
use Yii;
use common\models\User;
use backend\models\UserForm;
use backend\models\Company;
use yii\helpers\ArrayHelper;
use backend\services\mail\MailSender;
use frontend\services\custom\Debugger;
use yii\web\NotFoundHttpException;

/**
 * Class NetManagerController
 * @package frontend\controllers
 */
class NetManagerController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
        }

        return $this->render('index', [
            'model' => $model,
            'users' => $users,
            'balanceHolders' => $balanceHolders,
        ]);
    }

    /**
     * @return string
     */
    public function actionEmployees()
    {
        // $searchModel = new UserSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // $dataProvider->sort = [
        //     'defaultOrder' => ['created_at' => SORT_DESC],
        // ];

        $user = User::findOne(Yii::$app->user->id);
        $profile = UserProfile::findOne($user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
        }

        return $this->render('employees', [
            'users' => $users,
            'profile' => $profile
        ]);
    }

    /**
     * Create Employee & set role & position & birthday
     *
     * @return void
     */
    public function actionCreateEmployee()
    {
        if (Yii::$app->user->can('create_employee')) {
            $model = new UserForm();
            $model->setScenario('create');

            if ($model->load(Yii::$app->request->post())) {
                $model->other = $model->password;
                $model->save();

                $manager = User::findOne(Yii::$app->user->id);
                $user = User::findOne(['email' => $model->email]);

                $user->company_id = $manager->company_id;
                $user->save();

                // send invite mail
                $password = $model->other;
                $sendMail = new MailSender();
                $company = Company::findOne(['id' => $manager->company_id]);
                $user = User::findOne(['email' => $model->email]);
                $sendMail->sendInviteToCompany($user, $company, $password);

                Yii::$app->session->setFlash('success', Yii::t('backend', 'Send ' . $model->username . ' invite'));

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

    /**
     *  view one employee
     */
    public function actionViewEmployee()
    {
        if (Yii::$app->request->post()) {
            $model = User::findOne(['id' => Yii::$app->request->post('id')]);

            return $this->render('view', [
                'model' => $model
            ]);
        }
    }

    /**
     *  edit employee
     */
    public function actionEditEmployee($id)
    {
        $user = new UserForm();
        $user->setModel($this->findModel($id));
        $profile = UserProfile::findOne($id);

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            $isValid = $user->validate(false);
            $isValid = $profile->validate(false) && $isValid;
            if ($isValid) {
                $user->save(false);
                $profile->save(false);

                return $this->redirect(['/net-manager/employees']);
            }
        }

        return $this->render('create', [
            'user' => $user,
            'profile' => $profile,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
        ]);
    }

    /**
     *  method check access with role
     */
    private function accessDenied()
    {
        return Yii::$app->session->setFlash(
            'error',
            Yii::t('frontend', 'Access denied')
        );
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
