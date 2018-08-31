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
                'attribute' => 'fireproof_residue',
                'contentOptions' => ['class' => 'cell-financial'],
            ],
            'money_in_banknotes',
            'date_last_encashment',
            'counter_last_encashment',
            'in_banknotes',
            'counter_zeroing',
            'technical_injection'
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>