<?php

namespace backend\controllers;

use backend\models\Company;
use Yii;
use yii\web\Controller;
use backend\models\CompanySearch;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

class BasketController extends Controller
{
    public function actionIndex()
    {
        $link = 'links';

        return $this->render('index',[
            'link' => $link
        ]);
    }

    public function actionCompany()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->searchDeleted(Yii::$app->request->queryParams);

        return $this->render('view-deleted', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCompanyRestore($id)
    {
        $models = Company::find()->where([])->all();
        foreach ($models as $model) {
            if ($model->getAttribute('id') == $id) {
                $model->restore();
            }
        }

        return $this->redirect(['company']);
    }
}
