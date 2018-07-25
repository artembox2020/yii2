<?php

namespace frontend\controllers;

use common\models\UserProfile;
use frontend\models\AddressBalanceHolder;
use frontend\models\AddressBalanceHolderSearch;
use frontend\models\BalanceHolder;
use frontend\models\BalanceHolderSearch;
use frontend\models\Imei;
use frontend\models\ImeiSearch;
use frontend\models\WmMashine;
use frontend\models\OtherContactPerson;
use frontend\models\WmMashineSearch;
use frontend\services\globals\Entity;
use Yii;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\UserSearch;
use backend\models\UserForm;
use backend\models\Company;
use yii\di\Instance;
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
    /** @var int ONE */
    const ONE = 1;
    
    /** @var int ZERO */
    const ZERO = 0;

    /** @var int PAGE_SIZE */
    const PAGE_SIZE = 10;
    
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
        
        $model = new User();
        
        $dataProvider = $searchModel->searchEmployees(Yii::$app->request->queryParams);
        
        return $this->render('employees', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model
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

            $model->status = self::ONE;
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
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewEmployee($id)
    {
        $model = $this->findModel($id, new User());
        
        return $this->render('view-employee', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionEditEmployee($id)
    {
        $user = new UserForm();
        $user->setModel($this->findModel($id, new User()));
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

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteEmployee($id) 
    {
        $model = $this->findModel($id, new User());
        $model->softDelete();

        return $this->redirect("/net-manager/employees");
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
     * Finds the instance model based on its primary key value.
     *
     * @param integer $id
     * @return instance of the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $instance)
    {
        $entity = new Entity();
        
        return $entity->getUnitPertainCompany($id, $instance);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionBalanceHolders()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $searchModel = new BalanceHolderSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user->company);
            $company = \frontend\models\Company::findOne($user->company->id);
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('balance-holder/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'company' => $company
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewBalanceHolder($id)
    {
        $model = $this->findModel($id, new BalanceHolder());
        
        $dataProvider = new ActiveDataProvider([
            'query' => OtherContactPerson::find()->andWhere(['balance_holder_id' => $id])->orderBy("id ASC"),
            'pagination' => false
        ]);

        return $this->render('balance-holder/view-balance-holder', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Index page for addresses
     * In case of $balanceHolderId is set, the appropriate filter is applied
     * 
     * @param $balanceHolderId
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionAddresses($balanceHolderId = false)
    {
        $searchModel = new AddressBalanceHolderSearch();
        $entity = new Entity();
        
        // imeis list for AutoComplete widget
        $imeis = $entity->getFilteredStatusDataMapped(
            new Imei(), Imei::STATUS_OFF, ['id' => ['imei']]
        );
                
        $params = [
            'searchModel' => $searchModel,
            'imeis' => $imeis
        ];
        
        if (!$balanceHolderId) {
            
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $params['dataProvider'] = $dataProvider;

            return $this->render('addresses/addresses', $params);
        }
        else {
            
            $dataProvider = $searchModel->search(
                array_merge(Yii::$app->request->queryParams, ['balanceHolderId' => $balanceHolderId])
            );
            
            $model = $this->findModel($balanceHolderId, new BalanceHolder());
            $params = array_merge($params, ['model' => $model, 'dataProvider' => $dataProvider]);
        
            return $this->renderPartial('addresses/balance-holder-addresses', $params);
        }
    }

    /**
     * Binds imei to address and redirects to actionAddresses
     * 
     * @param integer $id
     * @param integer $foreignId
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAddressesBindToImei($id, $foreignId) 
    {
        $entity = new Entity();
        
        $imei = $entity->getUnitPertainCompany($foreignId, new Imei());
        $imei->bindToAddress($id);
        
        $redirectUrl = array_merge(['addresses'], Yii::$app->request->queryParams);
        
        return $this->redirect($redirectUrl);
    }
    
    /**
     * @return string|\yii\web\Response
     */
    public function actionWashpay()
    {
        $searchModel = new ImeiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = self::PAGE_SIZE;
        $entity = new Entity();
        // addresses list for AutoComplete widget
        $addresses = $entity->getFilteredStatusDataMapped(
            new AddressBalanceHolder(), 
            AddressBalanceHolder::STATUS_FREE, ['id' => ['address', 'floor'], ', ']
        );

        return $this->render('washpay/washpay', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'addresses' => $addresses
        ]);
    }
    
    /**
     * Binds address to imei and redirects to actionWashpay
     * 
     * @param integer $id
     * @param integer $foreignId
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionWashpayBindToAddress($id, $foreignId) 
    {
        $entity = new Entity();
        $imei = $entity->getUnitPertainCompany($id, new Imei());
        $imei->bindToAddress($foreignId);
        $redirectUrl = array_merge(['washpay'], Yii::$app->request->queryParams);

        return $this->redirect($redirectUrl);
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

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionWashpayUpdate($id)
    {
        $user = User::findOne(Yii::$app->user->id);
        $entity = new Entity();
        
        $imei = $this->findModel($id, new Imei());
        
        $addresses = $entity->getFilteredStatusDataMapped(
            new AddressBalanceHolder(), 
            AddressBalanceHolder::STATUS_FREE, ['id' => ['address', 'floor'], ', '],
            $imei->address_id
        );
        $addresses = ArrayHelper::map($addresses, 'id', 'value');

        if ($imei->load(Yii::$app->request->post())) {
            $imei->company_id = $user->company_id;
            $addressBalanceHolder = $this->findModel($imei->address_id, new AddressBalanceHolder());
            $imei->balance_holder_id = $addressBalanceHolder->balance_holder_id;
            $imei->is_deleted = false;
            $imei->bindToAddress($imei->address_id, true);
            $imei->save();
                
            $this->redirect(['/net-manager/washpay']);
        }

        return $this->render('washpay/washpay-update', [
            'imei' => $imei,
            'addresses' => $addresses,
        ]);
    }

    /**
     * @param $addressBalanceHolderId
     * @return string|\yii\web\Response
     */
    public function actionWashpayCreate($addressBalanceHolderId = null)
    {
        $user = User::findOne(Yii::$app->user->id);
        $entity = new Entity();
        $addressBalanceHolder = $entity->getUnitPertainCompany(
            $addressBalanceHolderId, new AddressBalanceHolder(), false
        );
        $addresses = $entity->getFilteredStatusDataMapped(
            new AddressBalanceHolder(), 
            AddressBalanceHolder::STATUS_FREE, ['id' => ['address', 'floor'], ', ']
        );
        $addresses = ArrayHelper::map($addresses, 'id', 'value');
        $imei = new Imei();

        if ($imei->load(Yii::$app->request->post())) {
            $imei->company_id = $user->company_id;
            $addressBalanceHolder = $this->findModel($imei->address_id, new AddressBalanceHolder());
            $imei->balance_holder_id = $addressBalanceHolder->balance_holder_id;
            $imei->is_deleted = false;
            $imei->bindToAddress($imei->address_id, true);
            $imei->save();
                
            $this->redirect(['/net-manager/washpay']);
        }

        return $this->render('washpay/washpay-create', [
            'imei' => $imei,
            'addresses' => $addresses,
            'addressBalanceHolder' => $addressBalanceHolder
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionOsnovnizasoby()
    {
        $searchModel = new WmMashineSearch();
        $dataProvider = $searchModel->searchWashMachine(Yii::$app->request->queryParams);

        return $this->render('wm-machine/osnovni-zasoby', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionWmMachineView($id)
    {
        $entity = new Entity();
        $model = $entity->getUnitPertainCompany($id, new WmMashine());

        return $this->render('wm-machine/wm-machine-view', [
            'model' => $model
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionWmMachineAdd()
    {
        $entity = new Entity();
        $imeis = $entity->getFilteredStatusData(new Imei());

        $model = new WmMashine();

        if ($model->load(Yii::$app->request->post())) {
            $im = Imei::findOne(['id' => $model->imei_id]);
            $ad = AddressBalanceHolder::findOne(['id' => $im->address_id]);
            $model->company_id = $im->company_id;
            $model->address_id = $im->address_id;
            $model->balance_holder_id = $ad->balance_holder_id;
            $model->save();

            return $this->redirect('/net-manager/osnovnizasoby');
        }

        return $this->render('wm-machine/wm-machine-add', [
            'model' => $model,
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
            return $this->redirect('osnovnizasoby');
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
