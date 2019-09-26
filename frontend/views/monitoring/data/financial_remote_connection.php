<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\components\responsive\GridView;
use yii\widgets\Pjax;
use frontend\services\globals\EntityHelper;

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
        'columns' => [
            [
                'attribute' => 'fireproof_residue',
                'contentOptions' => ['class' => 'cell-financial'],
                'format' => 'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/fireproof_residue.png',
                    ],
                    $searchModel->attributeLabels()['fireproof_residue']
                ),
                'value' => function($model) use ($searchModel)
                {

                    return (
                        \Yii::$app->formatter->asDecimal($model->fireproof_residue, 0).
                        EntityHelper::makePopupWindow(
                            [],
                            $searchModel->attributeLabels()['fireproof_residue'],
                            'top: -5px',
                            'height: 5px'
                        )
                    );
                }
            ],
            [
                'attribute' => 'money_in_banknotes',
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/money_in_banknotes.png',
                    ],
                    $searchModel->attributeLabels()['money_in_banknotes']
                ),
                'value' => function($model) use ($searchModel)
                {

                    return (
                        \Yii::$app->formatter->asDecimal($model->money_in_banknotes, 0).
                        EntityHelper::makePopupWindow(
                            [],
                            $searchModel->attributeLabels()['money_in_banknotes'],
                            'top: -5px',
                            'height: 5px'
                        )
                    );    
                }
            ],
            [
                'attribute' => 'date_sum_last_encashment',
                'contentOptions' => ['class' => 'cell-encashment'],
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/calendar.png',
                        '/static/img/monitoring/date_last_encashment.png',
                    ],
                    $searchModel->attributeLabels()['date_sum_last_encashment']
                ),
                'value' => function($model) use ($searchModel)
                {
                    return (
                        $searchModel->getScalarDateAndSumLastEncashmentByImeiId($model->imei_id).
                        EntityHelper::makePopupWindow(
                            [],
                            $searchModel->attributeLabels()['date_sum_last_encashment'],
                            'top: -5px',
                            'height: 5px'
                        )
                    );
                }
            ],
            [
                'attribute' => 'date_sum_pre_last_encashment',
                'contentOptions' => ['class' => 'cell-encashment'],
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/calendar.png',
                        '/static/img/monitoring/date_last_encashment.png',
                    ],
                    $searchModel->attributeLabels()['date_sum_pre_last_encashment']
                ),
                'value' => function($model) use ($searchModel)
                {
                    return (
                        $searchModel->getScalarDateAndSumPreLastEncashmentByImeiId($model->imei_id).
                        EntityHelper::makePopupWindow(
                            [],
                            $searchModel->attributeLabels()['date_sum_pre_last_encashment'],
                            'top: -5px',
                            'height: 5px'
                        )
                    );    
                }
            ],
            [
                'attribute' => 'in_banknotes',
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/in_banknotes.png',
                    ],
                    $searchModel->attributeLabels()['in_banknotes']
                ),
                'value' => function($model) use ($searchModel)
                {
                    return (
                        $model->in_banknotes.
                        EntityHelper::makePopupWindow(
                            [],
                            $searchModel->attributeLabels()['in_banknotes'],
                            'top: -5px',
                            'height: 5px'
                        )
                    );
                }
            ],
            [
                'label' => Yii::t('frontend', 'Remote Connnection'),
                'format' => 'raw',
                'value' => function($model) use($monitoringController, $searchModel)
                {
                    return $monitoringController->renderImeiCard($model->imeiRelation->id, $searchModel);
                },
               'contentOptions' => ['class' => 'financial all'],
               'headerOptions' => ['class' => 'financial all']
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>