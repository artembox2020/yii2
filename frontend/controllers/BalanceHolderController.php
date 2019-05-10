<?php

namespace frontend\controllers;

use frontend\services\custom\Debugger;
use frontend\services\logger\src\service\LoggerService;
use Yii;
use frontend\models\BalanceHolder;
use frontend\models\BalanceHolderSearch;
use frontend\services\globals\Entity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;

/**
 * BalanceHolderController implements the CRUD actions for BalanceHolder model.
 */
class BalanceHolderController extends Controller
{

    /** @var LoggerService  */
    private $service;

    public function __construct($id, $module, LoggerService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@storage/tmp',
            ]
        ];
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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);
        
        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
            $searchModel = new BalanceHolderSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model);
        } else {

            return $this->redirect('account/sign-in/login');
        }

        return $this->render('index', [
            'model' => $model,
            'balanceHolders' => $balanceHolders,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single BalanceHolder model.
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
     * Creates a new BalanceHolder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BalanceHolder();

        if ($model->load(Yii::$app->request->post())) {
            $user = User::findOne(Yii::$app->user->id);
            $model->company_id = $user->company_id;
            $model->is_deleted = false;
            $this->service->createLog($model, 'Create');
            $model->save();

            return $this->redirect(['/net-manager/view-balance-holder', 'id' => $model->id]);
        }

        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;

        }
        return $this->render('create', [
            'model' => $model,
            'balanceHolders' => $balanceHolders,
            'company' => $company
        ]);
    }

    /**
     * Updates an existing BalanceHolder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
``
        if ($model->load(Yii::$app->request->post())) {
            $this->service->createLog($model, 'Update');
            $model->save();

            return $this->redirect(['net-manager/view-balance-holder', 'id' => $model->id]);
        }

        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $company = $user->company;
            $balanceHolders = $company->balanceHolders;

        }

        return $this->render('update', [
            'model' => $model,
            'balanceHolders' => $balanceHolders,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)) {
            $model = $this->findModel($id);
            $this->service->createLog($model, 'Delete');
            $this->findModel($id)->softDelete();

            return $this->redirect(['/net-manager/balance-holders']);
        }
    }

    /**
     * @param $id
     * @return \yii\di\Instance|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $entity = new Entity();
        return $entity->getUnitPertainCompany($id, new BalanceHolder());
    }
}
