<?php

namespace frontend\controllers;

use frontend\models\CbLog;
use frontend\models\CbLogSearch;
use frontend\models\TempLog;
use frontend\models\WmLog;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use Yii;
use frontend\models\Jlog;
use frontend\models\WmMashine;
use frontend\models\GdMashine;
use frontend\models\JlogSearch;
use frontend\models\JlogInitSearch;
use frontend\models\JlogDataSearch;
use frontend\models\JlogDataCpSearch;
use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * JournalController implements the CRUD actions for journal logs
 */
class JournalController extends Controller
{
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
     * Lists all Jlog models.
     * 
     * @param bool $isEncashment
     * @return mixed
     */
    public function actionIndex($isEncashment = false)
    {
        if (!\Yii::$app->user->can('journal/index', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('error', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $searchModel = new JlogSearch();
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'type_packet', 'imei', 'address', 'id', 'selectionName', 'selectionCaretPos',
                'filterCondition' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'val1' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'val2' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'inputValue' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'sort', 'isEncashment',
                'JlogSearch' => [
                    'inputValue' => ['date'],
                    'val2' => ['date']
                ],
                'page_size'
            ]
        );

        $addresses = $searchModel->getAddressesMapped();
        $imeis = $searchModel->getImeisMapped();
        $params = $searchModel->setParams($searchModel, $params, $params);
        $dataProvider = $searchModel->search($params);
        $typePackets = Jlog::getTypePackets();
        $pageSizes = jlog::getPageSizes();
        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];
        $eventSelectors = [
            'keyup' => '.journal-filter-form input[name=imei], .journal-filter-form input[name=address]',
            'change' => '.journal-filter-form select, .journal-filter-form input[name=address], .journal-filter-form input[name=imei]'
        ];

        if (!empty($isEncashment)) {
            $params['type_packet'] = Jlog::TYPE_PACKET_ENCASHMENT;
        }

        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.journal-filter-form', $eventSelectors);
        $removeRedundantGrids = $entityHelper->removeRedundantGrids('.journal-grid-view');
        $columnFilterScript = $this->getColumnFilterScript($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'typePackets' => $typePackets,
            'params' => $params,
            'submitFormOnInputEvents' => $submitFormOnInputEvents,
            'removeRedundantGrids' => $removeRedundantGrids,
            'columnFilterScript' => $columnFilterScript,
            'addresses' =>  $addresses,
            'imeis' => $imeis,
            'journalController' => $this,
            'pageSizes' => $pageSizes
        ]);
    }

    /**
     * Lists all Jlog models by mashine id
     *
     * @param int $id
     * @param bool $mashineRedirectAction
     * @return mixed
     */
    public function actionIndexByMashine($id, $mashineRedirectAction = false)
    {
        $mashine = WmMashine::findOne($id);
        $session = Yii::$app->session;
        $markerIsActive = $session->isActive;
        if (!$markerIsActive) {
            $session->open();
        }

        if (!Yii::$app->request->isAjax && !$mashineRedirectAction) {
            $redirectUrl = array_merge([$session->get('mashineRedirectAction')], Yii::$app->request->queryParams);

            return $this->redirect($redirectUrl);
        } elseif ($mashineRedirectAction) {
            $session->set('mashineRedirectAction', $mashineRedirectAction);
        }

        if (!$markerIsActive) {
            $session->close();
        }

        $searchModel = new JlogSearch();
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'type_packet', 'imei', 'address', 'id', 'selectionName', 'selectionCaretPos',
                'filterCondition' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'val1' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'val2' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'inputValue' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'sort',
                'JlogSearch' => [
                    'from_date', 'to_date',
                    'inputValue' => ['date'],
                    'val2' => ['date']
                ],
                'page_size'
            ]
        );

        $imei = Imei::findOne($mashine->imei_id);
        $params['imei'] = $imei->imei;

        $params = $searchModel->setParams($searchModel, $params, $params);
        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];
        $dataProvider = $searchModel->searchByMashine($params, $mashine->id);
        $typePackets = Jlog::getTypePackets();
        $pageSizes = Jlog::getPageSizes();
        $eventSelectors = [
            'change' =>
                '.journal-filter-form select,'.
                '.journal-filter-form input#mashine-from-date,'.
                '.journal-filter-form input#mashine-to-date'
        ];
        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.journal-filter-form', $eventSelectors);
        $removeRedundantGrids = $entityHelper->removeRedundantGrids('.journal-grid-view');
        $columnFilterScript = $this->getColumnFilterScript($params);

        return $this->renderPartial('index-by-mashine', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'typePackets' => $typePackets,
            'params' => $params,
            'submitFormOnInputEvents' => $submitFormOnInputEvents,
            'removeRedundantGrids' => $removeRedundantGrids,
            'columnFilterScript' => $columnFilterScript,
            'journalController' => $this,
            'pageSizes' => $pageSizes
        ]);
    }

    /**
     * Renders journal logs
     *
     * @param array $prms
     * @return string
     */
    public function actionLogs($prms)
    {
        $searchModel = new CbLogSearch();
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'type_packet', 'imei', 'address', 'id', 'selectionName', 'selectionCaretPos',
                'wm_mashine_number',
                'filterCondition' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number',
                ],
                'val1' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number',
                ],
                'val2' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number',
                ],
                'inputValue' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number',
                ],
                'sort',
                'CbLogSearch' => [
                    'inputValue' => ['date'],
                    'val2' => ['date']
                ],
                'page_size'
            ]
        );

        $params = $searchModel->setParams($searchModel, $params, $prms);
        $dataProvider = $searchModel->search($params);

        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];

        return $this->renderPartial('logs/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params
        ]);
    }

    /**
     * Renders index journal view by actual  data
     *
     * @param ActiveDataProvider $dataProvider
     * @param JlogSearch $searchModel
     * @param array $params
     * @return string
     */
    public function renderAll($dataProvider, $searchModel, $params)
    {

        return $this->renderPartial('/journal/all/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'params' => $params
        ]);
    }

    /**
     * Renders journal initialization
     *
     * @param array $prms
     * @return string
     */
    public function actionInit($prms)
    {
        $searchModel = new JLogInitSearch();
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest([
            'type_packet', 'imei', 'address', 'id', 'selectionName', 'selectionCaretPos',
            'filterCondition' => [
                'date', 'type_packet', 'address', 'imei', 'id',
            ],
            'val1' => [
                'date', 'type_packet', 'address', 'imei', 'id',
            ],
            'val2' => [
                'date', 'type_packet', 'address', 'imei', 'id',
            ],
            'inputValue' => [
                'date', 'type_packet', 'address', 'imei', 'id',
            ],
            'sort',
            'page_size'
        ]);

        $params = $searchModel->setParams($searchModel, $params, $prms);

        $dataProvider = $searchModel->searchInit($params);

        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];

        return $this->renderPartial('init/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params
        ]);
    }

    /**
     * Renders journal data
     *
     * @param array $prms
     * @return string
     */
    public function actionData($prms)
    {
        $searchModel = new JLogDataSearch();
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest([
            'type_packet', 'imei', 'address', 'selectionName', 'selectionCaretPos',
            'filterCondition' => [
                'date', 'type_packet', 'address', 'imei', 'number_device',
            ],
            'val1' => [
                'date', 'type_packet', 'address', 'imei', 'number_device',
            ],
            'val2' => [
                'date', 'type_packet', 'address', 'imei', 'number_device',
            ],
            'inputValue' => [
                'date', 'type_packet', 'address', 'imei', 'number_device',
            ],
            'sort', 'page', 'page_size'
        ]);

        $params = $searchModel->setParams($searchModel, $params, $prms);

        $dataProvider = $searchModel->searchData($params);

        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];

        return $this->renderPartial('data/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    /**
     * Renders journal central board data
     *
     * @param array $prms
     * @return string
     */
    public function actionDataCp($prms)
    {
        $searchModel = new JLogDataCpSearch();
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest([
            'type_packet', 'imei', 'address', 'selectionName', 'selectionCaretPos',
            'filterCondition' => [
                'date', 'type_packet', 'address',
            ],
            'val1' => [
                'date', 'type_packet', 'address',
            ],
            'val2' => [
                'date', 'type_packet', 'address',
            ],
            'inputValue' => [
                'date', 'type_packet', 'address',
            ],
            'sort', 'page_size'
        ]);

        $params = $searchModel->setParams($searchModel, $params, $prms);

        $dataProvider = $searchModel->searchData($params);

        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];

        return $this->renderPartial('data/cp', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    /**
     * Runs encashment action
     *
     * @param array $params
     * @return string
     */
    public function actionEncashment($params)
    {
        return Yii::$app->runAction('encashment-journal/index', ['params' => array_merge($params, ['isEncashment' => 1])]);
    }

    /**
     * Gets the main journal js 
     * 
     * @param array $params
     * @return string
     */
    private function getColumnFilterScript($params)
    {

        return Yii::$app->view->render(
            "/journal/filters/columnFilter",
            [
                'today' => Yii::t('frontend', 'Today'),
                'tomorrow' => Yii::t('frontend', 'Tomorrow'),
                'yesterday' => Yii::t('frontend', 'Yesterday'),
                'lastweek' => Yii::t('frontend', 'Lastweek'),
                'lastmonth' => Yii::t('frontend', 'Lastmonth'),
                'lastyear' => Yii::t('frontend', 'Lastyear'),
                'certain' => Yii::t('frontend', 'Certain'),
                'params' => $params,
                'pageSize' => $params['page_size'] ? $params['page_size'] : JlogDataSearch::TYPE_PAGE_SIZE
            ]
        );
    }

    /**
     * Renders appropriate action regarding the packet type 
     * 
     * @param array $params
     * @return string
     */
    public function renderAppropriatePacket($params)
    {
        switch ($params['type_packet']) {
            case Jlog::TYPE_PACKET_LOG:

                return $this->actionLogs($params);
            case Jlog::TYPE_PACKET_INITIALIZATION:

                return $this->actionInit($params);
            case Jlog::TYPE_PACKET_DATA:

                return $this->actionData($params);
            case Jlog::TYPE_PACKET_DATA_CP:

                return $this->actionDataCp($params);
            case Jlog::TYPE_PACKET_ENCASHMENT:

                return $this->actionEncashment($params);    
        }
    }
}
