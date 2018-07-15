<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\AddressBalanceHolder;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use Yii;
use frontend\models\Imei;
use frontend\models\ImeiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ImeiController implements the CRUD actions for Imei model.
 */
class ImeiController extends Controller
{
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
            $balanceHolders = $user->company->balanceHolders;
        } else {
            //add flash нужно добавить компанию
            return $this->redirect('account/sign-in/login');
        }

        $searchModel = new ImeiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'balanceHolders' => $balanceHolders,
        ]);
    }

    /**
     * Displays a single Imei model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
//        $model = Imei::findOne($id);
//        Debugger::dd($model);
//        $res = $this->findModel($id);
//        Debugger::dd($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Imei model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Imei();

        $user = User::findOne(Yii::$app->user->id);
        $company = $user->company;

        foreach ($company->balanceHolders as $item) {
            foreach ($item->addressBalanceHolders as $result) {
                $address[] = $result;
            }
        }

        ArrayHelper::multisort($address, ['address'], [SORT_ASC]);

        if ($model->load(Yii::$app->request->post())) {
            $address_balance_holder = AddressBalanceHolder::findOne($model->address_id);
            $model->balance_holder_id = $address_balance_holder->id;
            $model->company_id = $company->id;
            $model->created_at = Time();
            $model->is_deleted = false;
            $model->deleted_at = time();
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'address' => $address
        ]);
    }

    /**
     * Updates an existing Imei model.
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

        foreach ($company->balanceHolders as $item) {
            foreach ($item->addressBalanceHolders as $result) {
                $address[] = $result;
            }
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'address' => $address
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['/net-manager/washpay']);
    }

    /**
     * @param $id
     * @return Imei|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $entity = new Entity();
        
        return $entity->getUnitPertainCompany($id, new Imei());
    }
}
