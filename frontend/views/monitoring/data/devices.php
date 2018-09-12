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
                    return Html::a(Yii::t('frontend', $model->type_mashine).++$index, '/wm-mashine/update?id='.$model->id);
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
                    return $model->getStateView();
                },
                'contentOptions' => ['class' => 'current-status']
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>