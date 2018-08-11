<?php

namespace frontend\controllers;

use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
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
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            ['type_packet', 'imei', 'address', 'selectionName', 'selectionCaretPos']
        );
        $dataProvider = $searchModel->search($params);
        $typePackets = Jlog::getTypePackets();
        $typePackets[''] = Yii::t('frontend', 'All');
        $eventSelectors = [
            'keyup' => 'input[name=imei], input[name=address]',
            'change' => 'select, input[name=address], input[name=imei]'
        ];

        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.journal-filter-form', $eventSelectors);
        $removeRedundantGrids = $entityHelper->removeRedundantGrids('.journal-grid-view');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'typePackets' => $typePackets,
            'params' => $params,
            'submitFormOnInputEvents' => $submitFormOnInputEvents,
            'removeRedundantGrids' => $removeRedundantGrids
        ]);
    }
}
