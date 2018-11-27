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
     * @return mixed
     */
    public function actionIndex()
    {
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
                    'inputValue' => ['date'],
                    'val2' => ['date']
                ]
            ]
        );

        $addresses = $searchModel->getAddressesMapped();
        $imeis = $searchModel->getImeisMapped();
        $dataProvider = $searchModel->search($params);
        $typePackets = Jlog::getTypePackets();
        $typePackets[''] = Yii::t('frontend', 'All');
        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];
        $eventSelectors = [
            'keyup' => '.journal-filter-form input[name=imei], .journal-filter-form input[name=address]',
            'change' => '.journal-filter-form select, .journal-filter-form input[name=address], .journal-filter-form input[name=imei]'
        ];

        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.journal-filter-form', $eventSelectors);
        $removeRedundantGrids = $entityHelper->removeRedundantGrids('.journal-grid-view');
        $columnFilterScript = Yii::$app->view->render(
            "/journal/filters/columnFilter",
            [
                'today' => Yii::t('frontend', 'Today'),
                'tomorrow' => Yii::t('frontend', 'Tomorrow'),
                'yesterday' => Yii::t('frontend', 'Yesterday'),
                'lastweek' => Yii::t('frontend', 'Lastweek'),
                'lastmonth' => Yii::t('frontend', 'Lastmonth'),
                'lastyear' => Yii::t('frontend', 'Lastyear'),
                'certain' => Yii::t('frontend', 'Certain'),
                'params' => $params
            ]
        );

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
            'journalController' => $this
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
                ]
            ]
        );

        if ($params['type_packet'] == Jlog::TYPE_PACKET_LOG) {

            return $this->redirect(['/journal/logs']);
        }

        $searchModel->from_date = $params['JlogSearch']['from_date'];
        $searchModel->to_date = $params['JlogSearch']['to_date'];
        $searchModel->mashineNumber = '_'.$mashine->type_mashine.'*'.$mashine->number_device;
        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];
        $dataProvider = $searchModel->searchByMashine($params, $mashine->id);
        $typePackets = Jlog::getTypePackets();
        $typePackets[''] = Yii::t('frontend', 'All');
        $eventSelectors = [
            'change' =>
                '.journal-filter-form select,'.
                '.journal-filter-form input#mashine-from-date,'.
                '.journal-filter-form input#mashine-to-date'
        ];
        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.journal-filter-form', $eventSelectors);
        $removeRedundantGrids = $entityHelper->removeRedundantGrids('.journal-grid-view');
        $columnFilterScript = Yii::$app->view->render(
            "/journal/filters/columnFilter",
            [
                'today' => Yii::t('frontend', 'Today'),
                'tomorrow' => Yii::t('frontend', 'Tomorrow'),
                'yesterday' => Yii::t('frontend', 'Yesterday'),
                'lastweek' => Yii::t('frontend', 'Lastweek'),
                'lastmonth' => Yii::t('frontend', 'Lastmonth'),
                'lastyear' => Yii::t('frontend', 'Lastyear'),
                'certain' => Yii::t('frontend', 'Certain'),
                'params' => $params
            ]
        );

        return $this->renderPartial('index-by-mashine', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'typePackets' => $typePackets,
            'params' => $params,
            'submitFormOnInputEvents' => $submitFormOnInputEvents,
            'removeRedundantGrids' => $removeRedundantGrids,
            'columnFilterScript' => $columnFilterScript,
        ]);
    }

    /**
     * Renders journal logs
     *
     * @return string
     */
    public function actionLogs()
    {
        $searchModel = new CbLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'type_packet', 'imei', 'address', 'id', 'selectionName', 'selectionCaretPos',
                'filterCondition' => [
                    'date', 'type_packet', 'address', 'imei', 'id', 'rate', 'device', 'signal',  'fireproof_counter_hrn', 'collection_counter',
                    'notes_billiards_pcs',
                    'wash_temperature'
                ],
                'val1' => [
                    'date', 'type_packet', 'address', 'imei', 'id', 'rate', 'device', 'signal', 'fireproof_counter_hrn', 'collection_counter',
                    'notes_billiards_pcs',
                    'wash_temperature'
                ],
                'val2' => [
                    'date', 'type_packet', 'address', 'imei', 'id', 'rate', 'device', 'signal', 'fireproof_counter_hrn', 'collection_counter',
                    'notes_billiards_pcs',
                    'wash_temperature'
                ],
                'inputValue' => [
                    'date', 'type_packet', 'address', 'imei', 'id', 'rate', 'device', 'signal', 'fireproof_counter_hrn', 'collection_counter',
                    'notes_billiards_pcs',
                    'wash_temperature'
                ],
                'sort',
                'CbLogSearch' => [
                    'inputValue' => ['date'],
                    'val2' => ['date']
                ]
            ]
        );

        $searchModel->inputValue['date'] = $params['inputValue']['date'];
        $searchModel->val2['date'] = $params['val2']['date'];

        $columnFilterScript = Yii::$app->view->render(
            "/journal/filters/columnFilter",
            [
                'today' => Yii::t('frontend', 'Today'),
                'tomorrow' => Yii::t('frontend', 'Tomorrow'),
                'yesterday' => Yii::t('frontend', 'Yesterday'),
                'lastweek' => Yii::t('frontend', 'Lastweek'),
                'lastmonth' => Yii::t('frontend', 'Lastmonth'),
                'lastyear' => Yii::t('frontend', 'Lastyear'),
                'certain' => Yii::t('frontend', 'Certain'),
                'params' => $params
            ]
        );

        return $this->renderPartial('logs/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'columnFilterScript' => $columnFilterScript,
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
}
