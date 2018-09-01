<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
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
            ],
            [
                'attribute' => 'date_last_encashment',
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/calendar.png',
                        '/static/img/monitoring/date_last_encashment.png',
                    ],
                    $searchModel->attributeLabels()['date_last_encashment']
                ),
            ],
            [
                'attribute' => 'counter_last_encashment',
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/fireproof_residue.png',
                        '/static/img/monitoring/date_last_encashment.png',
                    ],
                    $searchModel->attributeLabels()['counter_last_encashment']
                ),
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
            ],
            [
                'attribute' => 'counter_zeroing',
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/counter_zeroing.png',
                    ],
                    $searchModel->attributeLabels()['counter_zeroing']
                ),
            ],
            [
                'attribute' => 'technical_injection',
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/technical_injection.png',
                    ],
                    $searchModel->attributeLabels()['technical_injection']
                ),
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>