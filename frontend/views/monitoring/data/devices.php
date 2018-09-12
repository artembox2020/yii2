<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProviderWmMashine yii\data\ActiveDataProvider */
/* @var $dataProviderGdMashine yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\ImeiDataSearch */
?>
<div>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProviderWmMashine,
        'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            [
                'label' => Yii::t('frontend', 'Device'),
                'format' => 'raw',
                'attribute' => 'id',
                'value' => function($model)
                {

                    return Html::a(
                        Yii::t('frontend', $model->type_mashine).$model->number_device,
                        '/'.strtolower($model->type_mashine).'-mashine/view?id='.$model->id,
                        ['target' => '_blank']
                    );
                },
                'contentOptions' => ['class' => 'cell-device'],
            ],
            [
                'attribute' => 'bill_cash',
                'header' => \frontend\services\globals\EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/money_in_banknotes.png',
                    ],
                    $searchModel->attributeLabels()['money_in_banknotes']
                ),
                'contentOptions' => ['class' => 'bill-cash']
            ],
            [
                'attribute' => 'level_signal',
                'value' => function($model)
                {
                    return $model->getLevelSignal();
                },
                'header' => \frontend\services\globals\EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/signal_station2.png',
                    ],
                    $searchModel->attributeLabels()['level_signal']
                ),
                'contentOptions' => ['class' => 'level-signal']
            ],    
            [
                'attribute' => 'current_status',
                'format' => 'raw',
                'value' => function($model)
                {
                    return Html::a(
                        $model->getStateView(),
                        '/'.strtolower($model->type_mashine).'-mashine/view?id='.$model->id,
                        ['target' => '_blank']
                    );
                },
                'contentOptions' => ['class' => 'current-status']
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>