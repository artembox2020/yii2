<?php

namespace frontend\controllers;

use common\models\UserProfile;
use frontend\models\AddressBalanceHolder;
use frontend\models\AddressImeiData;
use frontend\models\AddressBalanceHolderSearch;
use frontend\models\BalanceHolder;
use frontend\models\BalanceHolderSearch;
use frontend\models\Imei;
use frontend\models\ImeiSearch;
use frontend\models\ImeiDataSearch;
use frontend\models\StorageHistory;
use frontend\models\StorageHistorySearch;
use frontend\models\TechnicalWork;
use frontend\models\WmMashine;
use frontend\models\OtherContactPerson;
use frontend\models\WmMashineSearch;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
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

    /** @var string  */
    const TYPE_WM = 'WM';

    /** @var int for wm status transfer form storage Junk status */
    const STATUS_JUNK = 3;

    /** @var int for wm status transfer form storage Under repair status */
    const STATUS_UNDER_REPAIR = 2;
    
    const DATA_MODEM_HISTORY_FORMAT = 'H:i:s d.m.y';
    
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

        if (!\Yii::$app->user->can('net-manager/index', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

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
        if (!\Yii::$app->user->can('net-manager/employees', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

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
     * @return string|\yii\web\Response
     * @throws \Exception
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

        if (!\Yii::$app->user->can('manager')) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

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
//        if (!\Yii::$app->user->can('manager')) {
//            \Yii::$app->getSession()->setFlash('error', 'Access denied');
//            return $this->render('@app/modules/account/views/denied/access-denied');
//        }

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
        if (!\Yii::$app->user->can('net-manager/balance-holders', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

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
     * @param bool $balanceHolderId
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function actionAddresses($balanceHolderId = false)
    {
        if (!\Yii::$app->user->can('net-manager/addresses', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $searchModel = new AddressBalanceHolderSearch();
        $entityHelper = new EntityHelper();
        $user = User::findOne(Yii::$app->user->id);
        $company = \frontend\models\Company::findOne($user->company->id);
        
        // imeis list for AutoComplete widget
        $imeis = $entityHelper->tryFilteredStatusDataMapped(
            new Imei(), Imei::STATUS_OFF, ['id' => ['imei']]
        );
                
        $params = [
            'searchModel' => $searchModel,
            'imeis' => $imeis
        ];
        
        if (!$balanceHolderId) {
            
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $params['dataProvider'] = $dataProvider;
            $params['company'] = $company;

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
        
        $addressImeiData = new AddressImeiData();
        $addressImeiData->createLog($imei->id, $id);

        $redirectUrl = array_merge(['addresses'], Yii::$app->request->queryParams);
        
        return $this->redirect($redirectUrl);
    }

    /**
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionWashpay()
    {
        if (!\Yii::$app->user->can('net-manager/washpay', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $searchModel = new ImeiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = self::PAGE_SIZE;
        $entityHelper = new EntityHelper();
        // addresses list for AutoComplete widget
        $addresses = $entityHelper->tryFilteredStatusDataMapped(
            new AddressBalanceHolder(), 
            AddressBalanceHolder::STATUS_FREE, ['id' => ['address', 'floor'], ', ']
        );

        return $this->render('washpay/washpay', [
            'searchModel' => $searchModel,
            'model' => new Imei(),
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
        $oldAddressId = $imei->address_id;
        $imei->bindToAddress($foreignId);

        $addressImeiData = new AddressImeiData();

        if ($foreignId != $oldAddressId) {
            $addressImeiData->createLog(0, $oldAddressId);
        }

        $addressImeiData->createLog($imei->id, $foreignId);

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
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function actionWashpayUpdate($id)
    {
        $user = User::findOne(Yii::$app->user->id);
        $entityHelper = new EntityHelper();

        $imei = $this->findModel($id, new Imei());
        $oldAddressId = $imei->address_id;

        $addresses = $entityHelper->tryFilteredStatusDataMapped(
            new AddressBalanceHolder(), 
            AddressBalanceHolder::STATUS_FREE,
            ['id' => ['address', 'floor'], ', '],
            $imei->status == Imei::STATUS_ACTIVE ? [$imei->address_id] : []
        );
        $addresses = ArrayHelper::map($addresses, 'id', 'value');

        if ($imei->load(Yii::$app->request->post())) {
            $imei->company_id = $user->company_id;
            $addressBalanceHolder = $this->findModel($imei->address_id, new AddressBalanceHolder());
            $imei->balance_holder_id = $addressBalanceHolder->balance_holder_id;
            $imei->is_deleted = false;
            $imei->bindToAddressIfActive($imei->address_id);
            $imei->save();
            
            $addressImeiData = new AddressImeiData();

            if ($imei->address_id != $oldAddressId) {
                $addressImeiData->createLog(0, $oldAddressId);
            }

            $addressImeiData->createLog($imei->id, $imei->address_id);

            $this->redirect(['/net-manager/washpay']);
        }

        return $this->render('washpay/washpay-update', [
            'imei' => $imei,
            'addresses' => $addresses,
        ]);
    }

    /**
     * @param null $addressBalanceHolderId
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function actionWashpayCreate($addressBalanceHolderId = null)
    {
        $user = User::findOne(Yii::$app->user->id);
        $entity = new Entity();
        $entityHelper = new EntityHelper();
        $addressBalanceHolder = $entity->tryUnitPertainCompany(
            $addressBalanceHolderId, new AddressBalanceHolder()
        );
        $addresses = $entityHelper->tryFilteredStatusDataMapped(
            new AddressBalanceHolder(), 
            AddressBalanceHolder::STATUS_FREE,
            ['id' => ['address', 'floor'], ', ']
        );
        $addresses = ArrayHelper::map($addresses, 'id', 'value');
        $imei = new Imei();

        if ($imei->load(Yii::$app->request->post())) {
            $imei->company_id = $user->company_id;
            $addressBalanceHolder = $this->findModel($imei->address_id, new AddressBalanceHolder());
            $imei->balance_holder_id = $addressBalanceHolder->balance_holder_id;
            $imei->is_deleted = false;
            $imei->bindToAddressIfActive($imei->address_id);
            $imei->save();

            $addressImeiData = new AddressImeiData();
            $addressImeiData->createLog($imei->id, $imei->address_id);

            $this->redirect(['/net-manager/washpay']);
        }

        return $this->render('washpay/washpay-create', [
            'imei' => $imei,
            'addresses' => $addresses,
            'addressBalanceHolder' => $addressBalanceHolder,
        ]);
    }

    /**
     * @return string
     */
    public function actionOsnovnizasoby()
    {
        $searchModel = new WmMashineSearch();
        $dataProvider = $searchModel->searchWashMachine(Yii::$app->request->queryParams);

        $model = new WmMashine();
        $array = $model->modelWm;
        $provider = new ArrayDataProvider([
            'allModels' => $array,
        ]);

//        $byDateProdution = $model->getByYearProduction();

        return $this->render('wm-machine/osnovni-zasoby', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
            'provider' => $provider
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

        $technical_work = TechnicalWork::find()->where(['machine_id' => $id])->all();

        $provider = new ArrayDataProvider([
            'allModels' => $technical_work,
        ]);

        return $this->render('wm-machine/wm-machine-view', [
            'model' => $model,
            'provider' => $provider
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

//        Debugger::dd($imeis);

        $model = new WmMashine();

        if ($model->load(Yii::$app->request->post())) {
            $im = Imei::findOne(['id' => $model->imei_id]);
            $ad = AddressBalanceHolder::findOne(['id' => $im->address_id]);
            $model->company_id = $im->company_id;
            $model->address_id = $im->address_id;
            $model->balance_holder_id = $ad->balance_holder_id;
            $model->is_deleted = self::ZERO;
            $model->type_mashine = self::TYPE_WM;

            if ($model->validate()) {
                $model->save(false);
            } else {

                return $this->render('wm-machine/wm-machine-add', [
                    'model' => $model,
                    'imeis' => $imeis
                ]);
            }

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
                        $address[] = $addresses->address;
                        $imeis[] = $imei;
                    }
                }
            }
        } else {

            return $this->redirect('account/sign-in/login');
        }
        if ($model->load(Yii::$app->request->post())) {
          if ($model->validate()) {
                $model->update(false);
                if ($model->status > self::ONE) {
                    $storage = new StorageHistory();
                    $storage->company_id = $model->company_id;
                    $storage->address_id = $model->address_id;
                    $storage->imei_id = $model->imei_id;
                    $storage->status = $model->status;
                    $storage->ping = $model->ping;
                    $storage->is_deleted = false;
                    $storage->number_device = $model->id;
                    $storage->type = $model->type_mashine;
                    $storage->insert();
                }
                if ($model->status == self::ONE
                    or $model->status == self::ZERO
                    or $model->status == self::STATUS_JUNK
                    or $model->status == self::STATUS_UNDER_REPAIR
                ) {
                    if (StorageHistory::find(['number_device' => $model->id])->one()) {
                        $st = StorageHistory::find(['number_device' => $model->id])->orderBy(['created_at'=>SORT_DESC])->one();
                        $st->status = $model->status;
                        if ($model->status == self::ZERO or $model->status == self::ONE) {
                            $st->date_transfer_from_storage = strtotime("now");
                        }
                        $st->update(false);
                    }

                    if (Yii::$app->request->post('TechnicalWork')['technical_work_data']) {

                        $technical_work = new TechnicalWork();
                        $technical_work->setWork($model,
                            Yii::$app->request->post('TechnicalWork')['technical_work_data']);
//                        Debugger::dd(Yii::$app->request->post('TechnicalWork')['technical_work_data']);
                    }

                }
              return $this->redirect('osnovnizasoby');
            }
        }

        $technical_work = new TechnicalWork();

        return $this->render('wm-machine/wm-machine-update', [
            'model' => $model,
            'company' => $company,
            'imeis' => $imeis,
            'addresses' => $address,
            'technical_work' => $technical_work
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionWmachineDelete($id)
    {
        $model = $this->findModel($id, new StorageHistory());
        $model->softDelete();

        return $this->redirect("/net-manager/fixed-assets");
    }

    /**
     * Storage history for Wash machine
     * @return string
     */
    public function actionFixedAssets()
    {
        $searchModel = new StorageHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('fixed-assets/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider

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

    /**
     * Gets modem history data by params or timestamp
     * 
     * @return array
     */
    public function getModemHistoryData($params, $timestamp)
    {
        $historyData = [];
        $addressImeiData = new AddressImeiData();

        if (!empty($params['imeiId'])) {

            $imei = Imei::find()->where(['id' => $params['imeiId']])->one();
            $historyData = $addressImeiData->getAddressHistoryByImei($imei);
        } elseif (!empty($params['addressId'])) {

            $address = AddressBalanceHolder::find()->where(['id' => $params['addressId']])->one();
            $historyData = $addressImeiData->getImeiHistoryByAddress($address);
        } elseif (!empty($params['timestamp'])) {

            $historyData = $addressImeiData->getHistoryByTimestamp($timestamp);
        } else {

            $historyData = $addressImeiData->getHistory();
        }

        return $historyData;
    }
    
    public function actionModemHistory()
    {
        if (!\Yii::$app->user->can('net-manager/modem-history', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->request->post());
        $imeis = $searchModel->getImeisMapped(Imei::findAllByCompany());
        $addresses = $searchModel->getAllAddressesMapped(AddressBalanceHolder::findAllByCompany());
        $entity = new Entity();
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'imei', 'address', 'imeiId', 'addressId', 'timestamp'
            ]
        );
        $searchModel->timestamp = !empty($params['timestamp']) ? strtotime($params['timestamp']) : null;

        $historyData = $this->getModemHistoryData($params, $searchModel->timestamp);        

        $dataProvider = new ArrayDataProvider([
            'allModels' => $historyData,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
            'sort' => [
                'attributes' => ['created_at'],
            ],
        ]);

        return $this->render('modem-history', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'imeis' => $imeis,
            'addresses' => $addresses,
            'params' => $params,
            'dateFormat' => self::DATA_MODEM_HISTORY_FORMAT
        ]);
    }
}
