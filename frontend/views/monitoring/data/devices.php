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
                'value' => function($model, $key, $index)
                {
                    return Yii::t('frontend', $model->type_mashine).++$index;
                },
                'contentOptions' => ['class' => 'cell-device'],
            ],
            'bill_cash',
            [
                'attribute' => 'level_signal',
                'value' => function($model)
                {
                    return $model->getLevelSignal();
                },
                'header' => \frontend\services\globals\EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/signal_station.png',
                        '/static/img/monitoring/signal_station2.png',
                    ],
                    $searchModel->attributeLabels()['level_signal']
                )
            ],    
            [
                'attribute' => 'current_status',
                'format' => 'raw',
                'value' => function($model)
                {
                    return $model->getStateView();
                }
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>