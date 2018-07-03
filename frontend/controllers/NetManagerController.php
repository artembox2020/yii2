<?php

namespace frontend\controllers;

use common\models\UserProfile;
use frontend\models\AddressBalanceHolder;
use frontend\models\BalanceHolder;
use frontend\models\BalanceHolderSearch;
use frontend\models\Imei;
use frontend\models\WmMashine;
use frontend\models\OtherContactPerson;
use Yii;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\UserSearch;
use backend\models\UserForm;
use backend\models\Company;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use backend\services\mail\MailSender;
use frontend\services\custom\Debugger;
use yii\web\NotFoundHttpException;

/**
 * Class NetManagerController
 * @package frontend\controllers
 */
class NetManagerController extends \yii\web\Controller
{
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
        ];
    }
    
    /**
     * Check whether user is a member of your company
     * @param $user_id
     * @return true | false
     */
    private function checkCompanyMember($user_id = false) {
        $currentUser = User::findOne(Yii::$app->user->id);
        $user = User::findOne($user_id);
        if(empty($currentUser) || empty($currentUser->company) || empty($user) || empty($user->company)) return false;
        if($currentUser->company->id == $user->company->id) return true;
        return false; 
    }
    
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
        $searchModel = new UserSearch();
        
        $dataProvider = $searchModel->searchEmployees(Yii::$app->request->queryParams);
        
        return $this->render('employees', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
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

        return $this->render('/denied/access-denied', [
            $this->accessDenied()
        ]);
    }

    /**
     *  view one employee
     */
    public function actionViewEmployee()
    {
        if (Yii::$app->request->get()) {
            
            if(!$this->checkCompanyMember(Yii::$app->request->get()['id'])) {
                
                return $this->redirect(['account/default/denied']);
            }
            
            $model = User::find()->where([ 'id' => Yii::$app->request->get()['id'] ]);
            
            return $this->render('view-employee', [
                'model' => $model->one()
            ]);
        }
    }

    /**
     *  edit employee
     */
    public function actionEditEmployee($id)
    {
        if(!$this->checkCompanyMember(Yii::$app->request->get()['id'])) {
                
            return $this->redirect(['account/default/denied']);
        }
        
        $user = new UserForm();
        $user->setModel($this->findModel($id));
        $profile = UserProfile::findOne($id);
        
        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            $profile->birthday = strtotime(Yii::$app->request->post()['UserProfile']['birthday']);
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

    public function actionDeleteEmployee($id) {
        
        if(!$this->checkCompanyMember(Yii::$app->request->get()['id'])) {
                
            return $this->redirect(['account/default/denied']);
        }
        
        $user = User::find()->where(['id' => $id])->one();
        $user->softDelete();
        
        $this->redirect("/net-manager/employees");
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
        if (($model = User::find()->where(['id' => $id])->one()) !== null) {
            return $model;
       } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionBalanceHolders()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
            $searchModel = new BalanceHolderSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('balance-holder/index', [
//            'model' => $model,
//            'users' => $users,
//            'balanceHolders' => $balanceHolders,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionViewBalanceHolder($id)
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
            $model = $this->findBalanceHolder($balanceHolders, $id);
            $dataProvider = new ActiveDataProvider([
                'query' => OtherContactPerson::find()->andWhere(['balance_holder_id' => $id])->orderBy("id ASC"),
                'pagination' => false
            ]);
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('balance-holder/view-balance-holder', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param $array
     * @param $id
     * @return null
     */
    private function findBalanceHolder($array, $id)
    {
        foreach ($array as $value) {
            if ($value->id == $id) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionAddresses()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('addresses/addresses', [
            'model' => $model,
            'users' => $users,
            'balanceHolders' => $balanceHolders,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionWashpay()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $company = $user->company;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('washpay/washpay', [
            'company' => $company,
            'model' => $model,
            'users' => $users,
            'balanceHolders' => $balanceHolders,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionWashpayView($id)
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
            $imei = Imei::findOne($id);
            $address = AddressBalanceHolder::findOne($imei->address_id);
            $balanceHolder = $address->balanceHolder;
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('washpay/washpay-view', [
            'model' => $model,
            'users' => $users,
            'balanceHolders' => $balanceHolders,
            'imei' => $imei,
            'address' => $address,
            'balanceHolder' => $balanceHolder
        ]);
    }

    public function actionWashpayUpdate($id)
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;
            foreach ($company->balanceHolders as $balanceHolder) {
                foreach ($balanceHolder->addressBalanceHolders as $addresses) {
                    $tempadd[] = $addresses;
                    foreach ($addresses->imeis as $value) {
                        if ($value->id == $id) {
                            $imei = $value;
                        }
                    }
                }
            }
                $address = AddressBalanceHolder::findOne($imei->address_id);
                $balanceHolder = $address->balanceHolder;

            if ($imei->load(Yii::$app->request->post())) {
                $imei->save();
                return $this->redirect('washpay');
            }

        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('washpay/washpay-update' , [
            'company' => $company,
            'imei' => $imei,
            'address' => $address,
            'addresses' => $tempadd,
            'balanceHolder' => $balanceHolder,
            'balanceHolders' => $balanceHolders
        ]);
    }

    public function actionWashpayCreate()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;
            foreach ($company->balanceHolders as $balanceHolder) {
                foreach ($balanceHolder->addressBalanceHolders as $addresses) {
                    $tempadd[] = $addresses;
                    }
                }
            $imei = new Imei();
            $address = new AddressBalanceHolder();

            if ($imei->load(Yii::$app->request->post())) {
                $imei->company_id = $company->id;
                $imei->save();
                return $this->redirect('washpay');
            }
        }

        return $this->render('washpay/washpay-create', [
            'company' => $company,
            'imei' => $imei,
            'address' => $address,
            'addresses' => $tempadd,
            'balanceHolders' => $balanceHolders
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionWmMachine()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('wm-machine/wm-machine', [
            'model' => $model,
            'users' => $users,
            'balanceHolders' => $balanceHolders,
        ]);
    }

    public function actionWmMachineView($id)
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
            $wm_machine = WmMashine::findOne($id);
//            $imei = WmMashine::findOne($id);
//            $address = AddressBalanceHolder::findOne($imei->id);
//            $balanceHolder = BalanceHolder::findOne($address->balance_holder_id);
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('wm-machine/wm-machine-view', [
            'wm_machine' => $wm_machine,
//            'imei' => $imei,
//            'address' => $address,
//            'balanceHolder' => $balanceHolder
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionWmMachineAdd()
    {
        $model = new WmMashine();

        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;
            foreach ($company->balanceHolders as $balanceHolder) {
                foreach ($balanceHolder->addressBalanceHolders as $addresses) {
                    foreach ($addresses->imeis as $imei) {
                        $imeis[] = $imei;
                    }
                }
            }
        } else {

            return $this->redirect('account/sign-in/login');
        }

        if ($model->load(Yii::$app->request->post())) {

//            Debugger::dd($model->imei_id);
            $im = Imei::findOne(['id' => $model->imei_id]);
            $ad = AddressBalanceHolder::findOne(['id' => $im->address_id]);
//            Debugger::dd($im);
            $model->balance_holder_id = $ad->balance_holder_id;
            $model->save(false);
            return $this->redirect('wm-machine');
        }

//        Debugger::dd($imeis);
        return $this->render('wm-machine/wm-machine-add', [
            'model' => $model,
            'company' => $company,
            'balanceHolders' => $balanceHolders,
            'imeis' => $imeis
        ]);
    }

    public function actionWmMachineUpdate($id)
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;
            $model = WmMashine::findOne($id);
            foreach ($company->balanceHolders as $balanceHolder) {
                foreach ($balanceHolder->addressBalanceHolders as $addresses) {
                    foreach ($addresses->imeis as $imei) {
                        $imeis[] = $imei;
                    }
                }
            }
        } else {

            return $this->redirect('account/sign-in/login');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->save(false);
            return $this->redirect('wm-machine');
        }

        return $this->render('wm-machine/wm-machine-update', [
            'model' => $model,
            'company' => $company,
            'imeis' => $imeis,
//            'address' => $address,
//            'balanceHolder' => $balanceHolder
        ]);
    }

    /**
     * @return string
     */
    public function actionFixedAssets()
    {
        $imeis = Imei::getStatusOff();
        $wm_machines = WmMashine::getStatusOff();

        return $this->render('fixed-assets/index', [
            'imeis' => $imeis,
            'wm_machines' => $wm_machines

        ]);
    }

    public function actionFixedAssetsUpdateImei($id)
    {
        $imei = Imei::find()->where(['id' => $id])->andWhere(['status' => Imei::STATUS_OFF])->one();

        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;
            foreach ($company->balanceHolders as $balanceHolder) {
                foreach ($balanceHolder->addressBalanceHolders as $addresses) {
                    $tempadd[] = $addresses;
                }
            }
            $address = AddressBalanceHolder::find(['id' => $imei->address_id])->one();
            $balanceHolder = $address->balanceHolder;

            if ($imei->load(Yii::$app->request->post())) {
                $imei->save();
                return $this->redirect('washpay');
            }

        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('fixed-assets/update-imei', [
            'company' => $company,
            'imei' => $imei,
            'address' => $address,
            'addresses' => $tempadd,
            'balanceHolders' => $balanceHolders,
            'balanceHolder' => $balanceHolder
        ]);
    }

    public function actionFixedAssetsUpdateWmMachine($id)
    {
        $wm_machine = WmMashine::find()->where(['id' => $id])->andWhere(['status' => Imei::STATUS_OFF])->one();
        $user = User::findOne(Yii::$app->user->id);
        $imei = $wm_machine->imei;
//        Debugger::dd($imei);
        if (!empty($user->company)) {
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;
            foreach ($company->balanceHolders as $balanceHolder) {
                foreach ($balanceHolder->addressBalanceHolders as $addresses) {
                    $imeis = $addresses->imeis;
                    $tempadd[] = $addresses->address;
                }
            }
//            $address = AddressBalanceHolder::find(['id' => $imei->address_id])->one();
//            $balanceHolder = $address->balanceHolder;

            if ($wm_machine->load(Yii::$app->request->post())) {
                $wm_machine->save();
                return $this->redirect('wm-machine');
            }

        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('fixed-assets/update-wm-machine', [
            'company' => $company,
            'imeis' => $imeis,
            'imei' => $imei,
//            'address' => $address,
            'addresses' => $tempadd,
            'wm_machine' => $wm_machine,
            'balanceHolders' => $balanceHolders,
            'balanceHolder' => $balanceHolder
        ]);
    }
}
