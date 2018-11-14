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
        ['attribute' => 'device',
            'label' => Yii::t('logs', 'Device'),
            'value'=> function ($model) {
            if ($model['device'] == 'wm') {
                return $model['device'] .' ' . $model['number'];
            }
                        return $model['device'];
            },
        ],
        ['attribute' => 'signal',
            'label' => Yii::t('logs', 'Signal level'),
            'value' => 'signal',
        ],
        ['attribute' => 'status',
            'label' => Yii::t('logs', 'Event'),
            'value' => 'status',
        ],
        ['attribute' => 'device',
            'label' => Yii::t('logs', 'Amount of replenishment, UAH (Securities) or Price of service, UAH (PM, GD)'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    return $model['price'];
                }
                return $model['refill_amount'];
            },
        ],
        ['attribute' => 'fireproof_counter_hrn',
            'label' => Yii::t('logs', 'Non-inflationary balance (CP), UAH or amount of money on the account (PM), UAH'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    return $model['account_money'];
                }
                return $model['fireproof_counter_hrn'];
            },
        ],
        ['attribute' => 'collection_counter',
            'label' => Yii::t('logs', 'Collection (Securities), UAH or amount of replenishment (SM), UAH'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    return $model['account_money'];
                }
                return $model['collection_counter'];
            },
        ],
        ['attribute' => 'notes_billiards_pcs',
            'label' => Yii::t('logs', 'Bonds in bills'),
            'value'=> function ($model) {
                return $model['notes_billiards_pcs'];
            },
        ],
        ['attribute' => 'washing_mode',
            'label' => Yii::t('logs', 'Washing mode (SM) or Gel issued (DH), ml.'),
            'value'=> function ($model) {
                return $model['washing_mode'];
            },
        ],
        ['attribute' => 'wash_temperature',
            'label' => Yii::t('logs', 'Washing temperature or gel residue, ml.'),
            'value'=> function ($model) {
                return $model['wash_temperature'];
            },
        ],
        ['attribute' => 'spin_type',
            'label' => Yii::t('logs', 'Spin type'),
            'value'=> function ($model) {
                return $model['spin_type'];
            },
        ],
        ['attribute' => 'Additional washing options',
            'label' => Yii::t('logs', 'Additional Washing Options'),
            'value'=> function ($model) {
    if ($model['prewash']) {
        return $model['prewash'];
    }
//    if ($model['rinsing']) {
//        return $model['rinsing'];
//    }
//    if ($model['intensive_wash']) {
//        return $model['intensive_wash'];
//    }
            },
        ],
//        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
</div>
