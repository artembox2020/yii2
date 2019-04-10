<?php

namespace backend\controllers;

use backend\models\Company;
use common\models\User;
use Yii;
use yii\web\Controller;
use backend\models\CompanySearch;
use backend\models\search\UserSearch;

class BasketController extends Controller
{
    public function actionIndex()
    {
        $link = 'links';

        return $this->render('index',[
            'link' => $link
        ]);
    }

    /**
     * @return string
     */
    public function actionCompany()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->searchDeleted(Yii::$app->request->queryParams);

        return $this->render('view-deleted', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @return string
     */
    public function actionUser()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchDeleted(Yii::$app->request->queryParams);

        return $this->render('user-deleted', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionCompanyRestore($id)
    {
        $models = Company::find()->where([])->all();
        foreach ($models as $model) {
            if ($model->getAttribute('id') == $id) {
                $model->restore();
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('backend', 'Company has been restored.'));

        return $this->redirect(['company']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionUserRestore($id)
    {
        $users = User::find()->where([])->all();
        foreach ($users as $user) {
            if ($user->getAttribute('id') == $id) {
                $user->restore();
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('backend', 'User has been restored.'));

        return $this->redirect(['user']);
    }
}
