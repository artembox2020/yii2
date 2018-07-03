<?php

namespace frontend\controllers;

use frontend\services\custom\Debugger;
use Yii;
use frontend\models\BalanceHolder;
use frontend\models\BalanceHolderSearch;
use frontend\services\globals\Entity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;

/**
 * BalanceHolderController implements the CRUD actions for BalanceHolder model.
 */
class BalanceHolderController extends Controller
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
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
     * Deletes an existing BalanceHolder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BalanceHolder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BalanceHolder the loaded model
     */
    protected function findModel($id)
    {
        $user = Entity::findOne(Yii::$app->user->id);
        return $user->getEntity($id,'balanceHolder');
    }
}
