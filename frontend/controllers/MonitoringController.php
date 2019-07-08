<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\ImeiDataSearch;
use frontend\models\ImeiAction;
use frontend\models\WmMashineDataSearch;
use frontend\models\WmMashine;
use frontend\models\Jlog;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use frontend\components\MonitoringBuilder;

class MonitoringController extends \frontend\controllers\Controller
{
    const SMALL_DEVICE_WIDTH = 512;
    const SORT_BY_ADDRESS = 0;
    const SORT_BY_SERIAL = 1;
    const TYPE_TIMEOUT = 300;

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
     * Main monitoring action, aggregates all monitoring shapters
     * 
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ImeiDataSearch();
        $searchModel->setSerialNumber(Yii::$app->request->post());
        $entityHelper = new EntityHelper();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->request->post());
        $addresses = $searchModel->getAddressesMapped($dataProvider->query);
        $imeis = $searchModel->getImeisMapped($dataProvider->query);
        $params = $entityHelper->makeParamsFromRequest(
            [
                'address', 'imei', 'id', 'sortOrder'
            ]
        );

        $monitoringShapters = [
            'common' => Yii::t('frontend', 'Common Data'),
            'financial' => Yii::t('frontend', 'Financial Data'),
            'devices' => Yii::t('frontend', 'Devices'),
            'terminal' => Yii::t('frontend', 'Terminal'),
            'all' => Yii::t('frontend', 'All')
        ];

        $sortOrders = [
            self::SORT_BY_ADDRESS => Yii::t('frontend', 'Sort By Address'),
            self::SORT_BY_SERIAL => Yii::t('frontend', 'Sort BY Serial Number')
        ];

        $script = $this->renderPartial(
            $this->getPath("/monitoring/data/script"),
            [
                'smallDeviceWidth' => self::SMALL_DEVICE_WIDTH,
                'numberRedundantHeaders' => 5,
                'timestamp' => time() + Jlog::TYPE_TIME_OFFSET,
                'timeOut' => self::TYPE_TIMEOUT
            ]
        );

        return $this->render($this->getPath('index'), [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringBuilder' => new MonitoringBuilder($this),
            'monitoringController' => $this,
            'monitoringShapters' => $monitoringShapters,
            'script' => $script,
            'addresses' => $addresses,
            'imeis' => $imeis,
            'params' => $params,
            'sortOrders' => $sortOrders,
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
     * @param int $imeiId
     * @param ImeiDataSearch $searchModel
     * @return string
     */
    public function renderImeiCard($imeiId, $searchModel)
    {
        global $dProvider;
        $this->setGlobals($imeiId, $searchModel);

        return $this->renderPartial('/monitoring/data/card', [
            'searchModel' => $searchModel,
            'dataProvider' => $dProvider
        ]);
    }

    /**
     * Renders monitoring terminal by imei id
     * 
     * @param $imeiId
     * @param ImeiDataSearch $searchModel
     * @return string
     */
    public function renderTerminalDataByImeiId($imeiId, $searchModel)
    {
        global $dProvider;
        $this->setGlobals($imeiId, $searchModel);

        return Yii::$app->view->render('/monitoring/data/terminal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dProvider,
            'monitoringController' => $this
        ]);
    }

    /**
     * Renders common data view by imei id
     *
     * @param int $imeiId
     * @param ImeiDataSearch $searchModel
     * @return string
     */
    public function renderCommonDataByImeiId($imeiId, $searchModel)
    {
        global $dProvider;
        $this->setGlobals($imeiId, $searchModel);

        return $this->renderPartial('/monitoring/data/common', [
            'searchModel' => $searchModel,
            'dataProvider' => $dProvider
        ]);
    }

    /**
     * Renders financial data + remote connection view by imei id
     * 
     * @param int $imeiId
     * @param ImeiDataSearch $searchModel
     * @return string
     */
    public function renderFinancialRemoteConnectionDataByImeiId($imeiId, $searchModel)
    {
        global $dProvider;
        $this->setGlobals($imeiId, $searchModel);

        return $this->renderPartial('/monitoring/data/financial_remote_connection', [
            'searchModel' => $searchModel,
            'dataProvider' => $dProvider,
            'monitoringController' => $this,
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
     * @return string
     */
    public function actionFinancial()
    {

        if (!\Yii::$app->user->can('viewFinData', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $searchModel = new ImeiDataSearch();
        $entityHelper = new EntityHelper();
        $searchModel->setSerialNumber(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->request->post());
        $addresses = $searchModel->getAddressesMapped($dataProvider->query);
        $imeis = $searchModel->getImeisMapped($dataProvider->query);
        $params = $entityHelper->makeParamsFromRequest(
            [
                'address', 'imei', 'id', 'sortOrder'
            ]
        );
        $monitoringShapters = [
            'common' => Yii::t('frontend', 'Common Data'),
            'financial' => Yii::t('frontend', 'Financial Data'),
            'all' => Yii::t('frontend', 'All')
        ];
        $sortOrders = [
            self::SORT_BY_ADDRESS => Yii::t('frontend', 'Sort By Address'),
            self::SORT_BY_SERIAL => Yii::t('frontend', 'Sort BY Serial Number')
        ];
        $script = $this->renderPartial(
            $this->getPath("/monitoring/data/script"),
            [
                'smallDeviceWidth' => self::SMALL_DEVICE_WIDTH,
                'numberRedundantHeaders' => 3,
                'timestamp' => time() + Jlog::TYPE_TIME_OFFSET,
                'timeOut' => self::TYPE_TIMEOUT
            ]
        );

        return $this->render($this->getPath('financial'), [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringController' => $this,
            'monitoringBuilder' => new MonitoringBuilder($this),
            'monitoringShapters' => $monitoringShapters,
            'script' => $script,
            'addresses' => $addresses,
            'imeis' => $imeis,
            'params' => $params,
            'sortOrders' => $sortOrders,
        ]);
    }

    /**
     * @return string
     */
    public function actionTechnical()
    {

        if (!\Yii::$app->user->can('viewTechData', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $searchModel = new ImeiDataSearch();
        $entityHelper = new EntityHelper();
        $searchModel->setSerialNumber(Yii::$app->request->post());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->request->post());
        $addresses = $searchModel->getAddressesMapped($dataProvider->query);
        $imeis = $searchModel->getImeisMapped($dataProvider->query);
        $params = $entityHelper->makeParamsFromRequest(
            [
                'address', 'imei', 'id', 'sortOrder'
            ]
        );
        $monitoringShapters = [
            'common' => Yii::t('frontend', 'Common Data'),
            'devices' => Yii::t('frontend', 'Devices'),
            'terminal' => Yii::t('frontend', 'Terminal'),
            'all' => Yii::t('frontend', 'All')
        ];
        $sortOrders = [
            self::SORT_BY_ADDRESS => Yii::t('frontend', 'Sort By Address'),
            self::SORT_BY_SERIAL => Yii::t('frontend', 'Sort BY Serial Number')
        ];
        $script = $this->renderPartial(
            $this->getPath("/monitoring/data/script"),
            [
                'smallDeviceWidth' => self::SMALL_DEVICE_WIDTH,
                'numberRedundantHeaders' => 4,
                'timestamp' => time() + Jlog::TYPE_TIME_OFFSET,
                'timeOut' => self::TYPE_TIMEOUT
            ]
        );

        return $this->render($this->getPath('technical'), [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'monitoringController' => $this,
            'monitoringBuilder' => new MonitoringBuilder($this),
            'monitoringShapters' => $monitoringShapters,
            'script' => $script,
            'addresses' => $addresses,
            'imeis' => $imeis,
            'params' => $params,
            'sortOrders' => $sortOrders,
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

    /**
     * Check whether monitoring data has been changed  
     * 
     * @param string $deviceIds
     * @param timestamp $timestamp
     * @return json
     */
    public function actionCheckMonitoringWmUpdate($deviceIds, $timestamp)
    {
        $searchModel = new WmMashineDataSearch();
        $checkResult = $searchModel->checkMonitoringWmUpdate($deviceIds, $timestamp);

        $responseResult = array();
        $responseResult['status'] = $checkResult;

        return json_encode($responseResult);
    }

    /**
     * Main imei action function  
     * 
     * @param integer $imeId
     * @param string $imei
     * @param string $action
     * @param bool $isCancel
     */
    public function actionImeiAction($imeiId, $imei, $action, $isCancel)
    {
        $searchModel = new ImeiAction();
        $searchModel->appendAction($imeiId, $imei, $action, $isCancel);
    }

    /**
     * Action renders monitoring terminal by imei id
     * 
     * @param $imeiId
     * @param ImeiDataSearch $searchModel
     * @return string
     */
    public function actionRenderTerminalDataByImeiId($imeiId, $searchModel)
    {
        $terminalDataView = $this->renderTerminalDataByImeiId($imeiId, $searchModel);

        return Yii::$app->view->render('/monitoring/data/terminal_action', [
            'terminalDataView' => $terminalDataView,
            'script' => $this->renderScriptTerminal()
        ]);
    }

    /**
     * Action renders main script
     *
     * @return string
     */
    public function renderScriptTerminal()
    {

        return Yii::$app->view->render(
            "/monitoring/data/script-terminal",
            [
                'smallDeviceWidth' => self::SMALL_DEVICE_WIDTH,
                'numberRedundantHeaders' => 4,
                'timestamp' => time() + Jlog::TYPE_TIME_OFFSET,
                'timeOut' => self::TYPE_TIMEOUT
            ]
        );
    }
}
