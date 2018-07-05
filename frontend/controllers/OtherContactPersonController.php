<?php

namespace frontend\controllers;

use common\models\User;
use frontend\services\custom\Debugger;
use Yii;
use frontend\models\OtherContactPerson;
use frontend\models\OtherContactPersonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
 * OtherContactPersonController implements the CRUD actions for OtherContactPerson model.
 */
class OtherContactPersonController extends Controller
{
    const NINE_DIGIT = 9;
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
     * @return update person link
     */
    public static function getUpdateLink($id) {
        return  Html::a(
            "[".Yii::t('frontend','Edit contact person')."]", 
            ['other-contact-person/update', 'id' => $id], 
            ['class' => 'btn btn-success', 'style' => 'color: #fff;']
        );
    }
    
    /**
     * @return delete person link
     */
    public static function getDeleteLink($id) {
        return Html::a(
            "[".Yii::t('frontend','Delete contact person')."]", 
            ['other-contact-person/delete', 'id' => $id], 
            [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => Yii::t('common', 'Delete Confirmation'),
                    'method' => 'post',
                ],
                'style' => 'color: #fff;'
            ]
        );
    }
    
    /**
     * @return create person link
     */
    public static function getCreateLink() {
        return Html::a(
            "[".Yii::t('frontend','Add contact person')."]",
            ['other-contact-person/create'],
            ['class' => 'btn btn-success', 'style' => 'color: #fff;']
        );
    }

    /**
     * Lists all OtherContactPerson models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OtherContactPersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OtherContactPerson model.
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
     * Creates a new OtherContactPerson model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OtherContactPerson();

        $user = User::findOne(Yii::$app->user->id);
        $users = $user->company->users;
        $company = $user->company;
        $balanceHolder = $company->balanceHolders;

        if ($model->load(Yii::$app->request->post())) {
            $bol = OtherContactPerson::findAll(['balance_holder_id' => $model->balance_holder_id]);
            $res = count($bol);
            if ($res > self::NINE_DIGIT) {
                Yii::$app->session->setFlash(
                    'error',
                    Yii::t('frontend', 'Limit reached! This Balance Holder reached the limit *10 of contact persons'));
                return $this->redirect(['/other-contact-person/create']);
            } else {
                $model->save();
            }

            return $this->redirect(['/net-manager/view-balance-holder', 'id' => $model->balance_holder_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'balanceHolder' => $balanceHolder
        ]);
    }

    /**
     * Updates an existing OtherContactPerson model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $user = User::findOne(Yii::$app->user->id);
        $users = $user->company->users;
        $company = $user->company;
        $balanceHolder = $company->balanceHolders;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/net-manager/view-balance-holder', 'id' => $model->balance_holder_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'balanceHolder' => $balanceHolder
        ]);
    }

    /**
     * Deletes an existing OtherContactPerson model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->softDelete();
        return $this->redirect(['/net-manager/view-balance-holder', 'id' => $model->balance_holder_id]);
    }

    /**
     * Finds the OtherContactPerson model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OtherContactPerson the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OtherContactPerson::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }
}
