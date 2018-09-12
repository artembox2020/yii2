<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\BalanceHolderSummarySearch;
use frontend\models\WmMashine;
use Yii;
use yii\filters\AccessControl;

class SummaryJournalController extends \yii\web\Controller
{
    const SMALL_DEVICE_WIDTH = 512;

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
     * Main summary journal action
     * 
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BalanceHolderSummarySearch();
        $dataProvider = $searchModel->baseSearch(Yii::$app->request->queryParams);
        $monitoringShapters = [
            'common' => Yii::t('frontend', 'Common Data'),
            'financial' => Yii::t('frontend', 'Financial Data'),
            'devices' => Yii::t('frontend', 'Devices'),
            'terminal' => Yii::t('frontend', 'Terminal'),
            'all' => Yii::t('frontend', 'All')
        ];
        $script = Yii::$app->view->render(
            "/monitoring/data/script",
            [
                'smallDeviceWidth' => 512,//self::SMALL_DEVICE_WIDTH,
                'numberRedundantHeaders' => 5
            ]
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringShapters' => $monitoringShapters,
            'script' => $script
        ]);
    }

    /**
     * Renders monitoring of devices by imei id
     * 
     * @return string
     */
    public function renderDevicesByImeiId($imeiId)
    {
        $searchModel = new ImeiDataSearch();
        $dataProviderWmMashine = $searchModel->searchWmMashinesByImeiId($imeiId);
        $dataProviderGdMashine = $searchModel->searchGdMashinesByImeiId($imeiId);

        return $this->renderPartial('/monitoring/data/devices', [
            'searchModel' => $searchModel,
            'dataProviderWmMashine' => $dataProviderWmMashine,
            'dataProviderGdMashine' => $dataProviderGdMashine
        ]);
    }

    /**
     * Renders monitoring imei card (remote connection) by imei id
     * 
     * @return string
     */
    public function renderImeiCard($imeiId)
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->searchImeiCardDataByImeiId($imeiId);

        return $this->renderPartial('/monitoring/data/card', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Renders monitoring terminal by imei id
     * 
     * @return string
     */
    public function renderTerminalDataByImeiId($imeiId)
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->searchImeiCardDataByImeiId($imeiId);

        return $this->renderPartial('/monitoring/data/terminal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringController' => $this
        ]);
    }
    
    /**
     * Renders common data view by imei id
     * 
     * @return string
     */
    public function renderCommonDataByImeiId($imeiId)
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->searchImeiCardDataByImeiId($imeiId);

        return $this->renderPartial('/monitoring/data/common', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Renders financial data + remote connection view by imei id
     * 
     * @param int $imeiId
     * @return string
     */
    public function renderFinancialRemoteConnectionDataByImeiId($imeiId)
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->searchImeiCardDataByImeiId($imeiId);

        return $this->renderPartial('/monitoring/data/financial_remote_connection', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringController' => $this,
        ]);
    }
    
    /**
     * Renders financial data view by imei id
     * 
     * @param int $imeiId
     * @return string
     */
    public function renderFinancialDataByImeiId($imeiId)
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->searchImeiCardDataByImeiId($imeiId);

        return $this->renderPartial('/monitoring/data/financial', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Financial monitoring action
     * 
     * @return string
     */
    public function actionFinancial()
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $monitoringShapters = [
            'common' => Yii::t('frontend', 'Common Data'),
            'financial' => Yii::t('frontend', 'Financial Data'),
            'all' => Yii::t('frontend', 'All')
        ];
        $script = Yii::$app->view->render(
            "/monitoring/data/script",
            [
                'smallDeviceWidth' => self::SMALL_DEVICE_WIDTH,
                'numberRedundantHeaders' => 3
            ]
        );

        return $this->render('financial', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringController' => $this,
            'monitoringShapters' => $monitoringShapters,
            'script' => $script
        ]);
    }

    /**
     * Technical monitoring action
     * 
     * @return string
     */
    public function actionTechnical()
    {
        $searchModel = new ImeiDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $monitoringShapters = [
            'common' => Yii::t('frontend', 'Common Data'),
            'devices' => Yii::t('frontend', 'Devices'),
            'terminal' => Yii::t('frontend', 'Terminal'),
            'all' => Yii::t('frontend', 'All')
        ];
        $script = Yii::$app->view->render(
            "/monitoring/data/script",
            [
                'smallDeviceWidth' => self::SMALL_DEVICE_WIDTH,
                'numberRedundantHeaders' => 4
            ]
        );

        return $this->render('technical', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringController' => $this,
            'monitoringShapters' => $monitoringShapters,
            'script' => $script
        ]);
    }
}
