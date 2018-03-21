<?php

namespace backend\controllers;

use backend\services\mail\MailSender;
use common\models\User;
use frontend\services\custom\Debugger;
use Yii;
use backend\models\Company;
use backend\models\CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
    const ZERO = 0;
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = Time();
            $model->is_deleted = false;
            $model->deleted_at = time();
            $model->save();

            //save relation user with company
            $user = User::findOne($model->sub_admin);
            $user->company_id = $model->id;
            $user->save();

            // send invite mail
            $password = $user->other;
//            Debugger::dd($user);
            $sendMail = new MailSender();
            $sendMail->sendInviteToCompany($user, $model, $password);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = Company::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $user = User::findOne($model->sub_admin);
            $user->company_id = $model->id;
            $user->save();

            $password = $user->other;
            $sendMail = new MailSender();
            $sendMail->sendInviteToCompany($user, $model, $password);


            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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

        Yii::$app->session->setFlash('success', Yii::t('backend', 'Company has been deleted.'));
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

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRestore($id)
    {
        $models = Company::find()->where([])->all();
        foreach ($models as $model) {
            $model->restore();
        }

        return $this->redirect(['index']);
    }

    public function actionViewDeleted()
    {
        return $this->render('view-deleted', [
            'models' => Company::find()->where([])->all()
        ]);
    }
}
