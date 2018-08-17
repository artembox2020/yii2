<?php

namespace frontend\controllers;

use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use Yii;
use frontend\models\Jlog;
use frontend\models\JlogSearch;
use frontend\models\AddressBalanceHolder;
use frontend\models\Imei;
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
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'type_packet', 'imei', 'address', 'id', 'selectionName', 'selectionCaretPos',
                'filterCondition' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'val1' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'val2' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'inputValue' => ['date', 'type_packet', 'address', 'imei', 'id'],
                'sort'
            ]
        );
        $addresses = $searchModel->getAddressesMapped();
        $imeis = $searchModel->getImeisMapped();
        $dataProvider = $searchModel->search($params);
        $typePackets = Jlog::getTypePackets();
        $typePackets[''] = Yii::t('frontend', 'All');
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
            'imeis' => $imeis
        ]);
    }
}
