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

    <p>
        <u><b><?= Yii::t('frontend', 'Wm Mashines') ?></b></u>
    </p>

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
                    return Yii::t('frontend', $model->type_mashine).$model->id;
                }
            ],
            'bill_cash',
            'level_signal',
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

    <p>
        <u><b><?= Yii::t('frontend', 'Gd Mashines') ?></b></u>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProviderGdMashine,
        'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            [
                'label' => Yii::t('frontend', 'Device'),
                'format' => 'raw',
                'attribute' => 'id',
                'value' => function($model)
                {
                    return Yii::t('frontend', $model->type_mashine).$model->id;
                }
            ],
            'bill_cash',
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