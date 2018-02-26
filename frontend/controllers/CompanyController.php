<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use frontend\models\Company;
use frontend\models\CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
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
                    'delete' => ['POST']
                ]
            ]
        ];
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('view_companies')) {
            $searchModel = new CompanySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('denied/access-denied', [
            $this->accessDenied()
        ]);
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->can('view_company')) {

            $users = User::find()->where(['company_id' => $id])->all();

            return $this->render('view', [
                'model' => $this->findModel($id),
                'users' => $users
            ]);
        }
        return $this->render ('denied/access-denied', [
            $this->accessDenied()
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_company')) {

            $model = new Company();

            if ($model->load(Yii::$app->request->post())) {
                $model->is_deleted = false;
                $model->deleted_at = Time();
                $user = User::findOne($model->sub_admin);
                $user->company_id = $model->id;
                $model->save();

                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }

        return $this->render ('denied/access-denied', [
            $this->accessDenied()
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_company')) {
            $model = Company::findOne($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $user = User::findOne($model->sub_admin);
                $user->company_id = $model->id;
                $user->save();

                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }

        return $this->render ('denied/access-denied', [
            $this->accessDenied()
        ]);
    }

    /**
     * Deletes an existing Company model.
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
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRestore($id)
    {
        $models = Company::find()->where([])->all();
        foreach ($models as $model) {
            if ($model->getAttribute('id') == $id) {
                $model->restore();
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }

    private function accessDenied()
    {
        return Yii::$app->session->setFlash(
            'error',
            Yii::t('frontend', 'Access denied')
        );
    }
}
