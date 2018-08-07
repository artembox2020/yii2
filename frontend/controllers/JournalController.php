<?php

namespace frontend\controllers;

use frontend\services\globals\Entity;
use Yii;
use frontend\models\Jlog;
use frontend\models\JlogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * JournalController implements the CRUD actions for journal logs
 */
class JournalController extends Controller
{

    /**
     * Lists all Jlog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
