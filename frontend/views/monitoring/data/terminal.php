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
        'columns' => [
            [
                'attribute' => 'bill_acceptance',
                'format' => 'raw',
                'value' => function($model)
                {
                    
                    return $model->getBillAcceptanceData();
                },
                'contentOptions' => ['class' => 'terminal all'],
                'headerOptions' => ['class' => 'terminal all']
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
                'attribute' => 'actions',
                'format' => 'raw',
                'contentOptions' => ['class' => 'terminal all'],
                'headerOptions' => ['class' => 'terminal all']
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>