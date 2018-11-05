<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CbLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
//$this->title = Yii::t('frontend', 'Events Journal');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-balance-holder-index">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'address',
            'label' => Yii::t('frontend', 'Address'),
            'value' => 'address.address'
        ],
        ['attribute' => 'date',
            'label' => Yii::t('frontend', 'Hour that date'),
            'value' => function($dataProvider) {
                return date('H:i:s d.m.Y', $dataProvider->date);
            },
        ],
        'imei',
//        'price',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<!--    --><?php //\frontend\services\custom\Debugger::dd($dataProvider); ?>
</div>
