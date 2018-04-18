<?php

namespace frontend\controllers;

use common\models\User;
use frontend\services\custom\Debugger;
use Yii;
use frontend\models\Imei;
use frontend\models\ImeiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $balanceHolder = $company->balanceHolders;
        // $address = $balanceHolder->getAddressBalanceHolders();

        foreach ($company->balanceHolders as $item) {
            foreach ($item->addressBalanceHolders as $result) {
                $address[] = $result;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        $balanceHolder = $company->balanceHolders;
        // $address = $balanceHolder->getAddressBalanceHolders();

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

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Imei::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }
}
