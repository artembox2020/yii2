<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \frontend\models\AddressBalanceHolder;

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
            'value'=> function ($model) {
                $address = AddressBalanceHolder::find(['id' => $model['address_id']])->one();
                return $address->address;
            },
        ],
        ['attribute' => 'date',
            'label' => Yii::t('frontend', 'Hour that date'),
            'value' => 'date',
            'format' => ['date', 'php:H:i:s d.m.Y']
        ],
        'imei',
        ['attribute' => 'rate',
            'label' => Yii::t('logs', 'Rate'),
            'value' => 'rate',
        ],
        'device',
        'signal',
        'sum',
        'fireproof_counter_hrn',
        'collection_counter',
        'notes_billiards_pcs',
        'washing_mode',
        'wash_temperature',
        'spin_type',
        'Additional washing options',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<!--    --><?php //\frontend\services\custom\Debugger::dd($dataProvider); ?>
</div>
