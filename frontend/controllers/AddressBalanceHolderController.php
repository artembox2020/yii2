<?php

namespace frontend\controllers;

use common\models\User;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use frontend\services\logger\src\service\LoggerService;
use Yii;
use frontend\models\BalanceHolder;
use frontend\models\AddressBalanceHolder;
use frontend\models\AddressImeiData;
use frontend\models\AddressBalanceHolderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AddressBalanceHolderController implements the CRUD actions for AddressBalanceHolder model.
 */
class AddressBalanceHolderController extends Controller
{

    /** @var LoggerService  */
    private $service;

    public function __construct($id, $module, LoggerService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AddressBalanceHolder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AddressBalanceHolderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AddressBalanceHolder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AddressBalanceHolder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $balanceHolderId
     * @return mixed
     */
    public function actionCreate($balanceHolderId = false)
    {
        $model = new AddressBalanceHolder();

        $user = User::findOne(Yii::$app->user->id);
        $company = $user->company;
        $balanceHolders = $company->balanceHolders;
        $entity = new Entity();
        $balanceHolder = $entity->tryUnitPertainCompany(
            $balanceHolderId, new BalanceHolder()
        );
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->created_at = Time();
            $model->status = AddressBalanceHolder::STATUS_FREE;
            $model->is_deleted = false;
            $model->deleted_at = time();
            $model->save();
            $this->service->createLog($model, 'Create');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'company' => $company,
            'balanceHolders' => $balanceHolders,
            'balanceHolder' => $balanceHolder
        ]);
    }

    /**
     * Updates an existing AddressBalanceHolder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $user = User::findOne(Yii::$app->user->id);
        $company = $user->company;
        $balanceHolders = $company->balanceHolders;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->service->createLog($model, 'Update');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'company' => $company,
            'balanceHolders' => $balanceHolders
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $address = AddressBalanceHolder::findOne($id);

        if ($address->fakeImei) {
            $addressImeiData = new AddressImeiData();
            $addressImeiData->createLog($address->fakeImei->id, 0);
            $addressImeiData->createLog(0, $id);
        }

        if ($this->findModel($id)) {
            $model = $this->findModel($id);
            $this->service->createLog($model, 'Delete');
            $this->findModel($id)->softDelete();

            return $this->redirect(['/net-manager/addresses']);
        }
    }

    /**
     * Finds the AddressBalanceHolder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id
     * @return null|Instance
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
       $entity = new Entity();
       return $entity->getUnitPertainCompany($id, new AddressBalanceHolder());
    }
}
