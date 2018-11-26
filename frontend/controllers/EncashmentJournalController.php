<?php

namespace frontend\controllers;

use frontend\models\CbLogSearch;
use frontend\services\custom\Debugger;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\ImeiDataSearch;

/**
 * Class EncashmentJournalController
 * @package frontend\controllers
 */
class EncashmentJournalController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->request->post());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEncashment()
    {
        $searchModel = new CbLogSearch();
        $dataProvider = $searchModel->getEncashment(Yii::$app->request->queryParams);

//        Debugger::dd($searchModel->getAddress());

        return $this->render('encashment', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Renders financial data view by imei id
     *
     * @param int $imeiId
     * @param ImeiDataSearch $searchModel
     * @return string
     */
    public function renderFinancialDataByImeiId($imeiId, $searchModel)
    {
        global $dProvider;
        $this->setGlobals($imeiId, $searchModel);

        return $this->renderPartial('/monitoring/data/financial', [
            'searchModel' => $searchModel,
            'dataProvider' => $dProvider,
        ]);
    }

    /**
     * Sets global variables $dProvider and $currentImeiId
     *
     * @param int $imeiId
     * @param ImeiDatasearch $searchModel
     */
    private function setGlobals($imeiId, $searchModel)
    {
        global $dProvider;
        global $currentImeiId;

        if (empty($currentImeiId) || $currentImeiId != $imeiId) {
            $dProvider = $searchModel->searchImeiCardDataByImeiId($imeiId);
            $currentImeiId = $imeiId;
        }
    }
}
