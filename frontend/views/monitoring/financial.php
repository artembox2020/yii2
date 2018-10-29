<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\ImeiDataSearch;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel  frontend\models\ImeiDataSearch*/
/* @var $monitoringController frontend\controllers\MonitoringController */

?>
<h1><?= Yii::t('frontend', 'Monitoring') ?></h1>
<div class="monitoring">
    <?=
        Yii::$app->view->render('data/filter_form', [
            'params' => $params,
            'addresses' => $addresses,
            'imeis' => $imeis,
            'monitoringShapters' => $monitoringShapters,
            'sortOrders' => $sortOrders
        ]);
    ?>
    <br><br>

    <?php
        Pjax::begin(['id' => 'monitoring-pjax-grid-container']);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'summary' => '',
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-financial'
        ],
        'rowOptions' => [
            'class' => 'rows'
        ],
        'columns' => [
            [
                'label' => $monitoringShapters['common'],
                'format' => 'raw',
                'value' => function($model) use($monitoringController, $searchModel)
                {

                    return $monitoringController->renderCommonDataByImeiId($model->id, $searchModel);
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'common all']
            ],
            [
                'label' => $monitoringShapters['financial'],
                'format' => 'raw',
                'value' => function($model) use($monitoringController, $searchModel)
                {

                    return $monitoringController->renderFinancialRemoteConnectionDataByImeiId($model->id, $searchModel);
                },
                'contentOptions' => ['class' => 'financial all'],
                'headerOptions' => ['class' => 'financial all']
            ],
        ],
    ]); ?>
    <?=
        Yii::$app->view->render('data/pjax_form', ['params' => $params]);
    ?>
</div>
<?php
    echo $script;
    Pjax::end();
?>
