<?php

namespace frontend\controllers;

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
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->getImeiData(Yii::$app->request->queryParams);

        return $this->render('index', [
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
