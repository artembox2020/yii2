<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\ImeiDataSearch */
?>
<div>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '',
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-terminal'
        ],
        'columns' => [
            [
                'attribute' => 'bill_acceptance',
                'format' => 'raw',
                'value' => function($model)
                {
                    
                    return $model->getBillAcceptanceData();
                },
                'contentOptions' => ['class' => 'terminal all'],
                'headerOptions' => ['class' => 'terminal all'],
                'header' => \frontend\services\globals\EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/billAcceptance.png',
                    ],
                    $searchModel->attributeLabels()['bill_acceptance']
                )
            ],
            [
                'attribute' => 'software_versions',
                'format' => 'raw',
                'value' => function($model)
                {

                   return $model->getSoftwareVersions();
                },
                'contentOptions' => ['class' => 'terminal all'],
                'headerOptions' => ['class' => 'terminal all']
            ],
            [
                'label' => Yii::t('frontend', 'Remote Connnection'),
                'format' => 'raw',
                'value' => function($model) use($monitoringController, $searchModel)
                {
                    return $monitoringController->renderImeiCard($model->imeiRelation->id, $searchModel);
                },
                'contentOptions' => ['class' => 'terminal all'],
                'headerOptions' => ['class' => 'terminal all']
            ],
            [
                'attribute' => 'actions',
                'format' => 'raw',
                'value' => function($model)
                {

                   return $model->getActions();
                },
                'contentOptions' => ['class' => 'terminal all'],
                'headerOptions' => ['class' => 'terminal all']
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>