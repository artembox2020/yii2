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
        'date:date',
        'imei',
//            'created_at',
//            'updated_at',
//            'is_deleted',
//            'deleted_at',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<!--    --><?php //\frontend\services\custom\Debugger::dd($dataProvider); ?>
</div>
