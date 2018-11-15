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
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'date',
            'label' => Yii::t('frontend', 'Hour that date'),
            'value' => 'date',
            'format' => ['date', 'php:H:i:s d.m.Y'],
            'filter' => $this->render('/journal/filters/main', ['name'=> 'date', 'params' => $params, 'searchModel' => $jLogSearchModel]),
        ],
        [
            'attribute' => 'imei',
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'rate',
            'label' => Yii::t('logs', 'Rate'),
            'value' => 'rate',
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'device',
            'label' => Yii::t('logs', 'Device'),
            'value'=> function ($model) {
            if ($model['device'] == 'wm') {
                return $model['device'] .' ' . $model['number'];
            }
                        return $model['device'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'signal',
            'label' => Yii::t('logs', 'Signal level'),
            'value' => 'signal',
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'status',
            'label' => Yii::t('logs', 'Event'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    $machine = new \frontend\models\WmMashine();
                    if (array_key_exists($model['status'], $machine->log_state)) {
                        return Yii::t('logs', $machine->log_state[$model['status']]);
                    }
                }
                $cbLog = new \frontend\models\CbLog();
                if (array_key_exists($model['status'], $cbLog->current_state)) {
                    return Yii::t('logs', $cbLog->current_state[$model['status']]);
                }
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'device',
            'label' => Yii::t('logs', 'Amount of replenishment, UAH (Securities) or Price of service, UAH (PM, GD)'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    return $model['price'];
                }
                return $model['refill_amount'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'fireproof_counter_hrn',
            'label' => Yii::t('logs', 'Non-inflationary balance (CP), UAH or amount of money on the account (PM), UAH'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    return $model['account_money'];
                }
                return $model['fireproof_counter_hrn'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'collection_counter',
            'label' => Yii::t('logs', 'Collection (Securities), UAH or amount of replenishment (SM), UAH'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    return $model['account_money'];
                }
                return $model['collection_counter'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'notes_billiards_pcs',
            'label' => Yii::t('logs', 'Bonds in bills'),
            'value'=> function ($model) {
                return $model['notes_billiards_pcs'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'washing_mode',
            'label' => Yii::t('logs', 'Washing mode (SM) or Gel issued (DH), ml.'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    $machine = new \frontend\models\WmMashine();
                    if (array_key_exists($model['washing_mode'], $machine->washing_mode)) {
                        return Yii::t('logs', $machine->washing_mode[$model['washing_mode']]);
                    }
                }
                return $model['washing_mode'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'wash_temperature',
            'label' => Yii::t('logs', 'Washing temperature or gel residue, ml.'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    $machine = new \frontend\models\WmMashine();
                    if (array_key_exists($model['wash_temperature'], $machine->wash_temperature)) {
                        return Yii::t('logs', $machine->wash_temperature[$model['wash_temperature']]);
                    }
                }
                return $model['wash_temperature'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'spin_type',
            'label' => Yii::t('logs', 'Spin type'),
            'value'=> function ($model) {
                if ($model['device'] == 'wm') {
                    $machine = new \frontend\models\WmMashine();
                    if (array_key_exists($model['spin_type'], $machine->spin_type)) {
                        return Yii::t('logs', $machine->spin_type[$model['spin_type']]);
                    }
                }
                return $model['spin_type'];
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        ['attribute' => 'Additional washing options',
            'label' => Yii::t('logs', 'Additional Washing Options'),
            'value'=> function ($model) {
    if ($model['prewash']) {
        if ($model['device'] == 'wm') {
            return $model['prewash'] . ' ' . $model['rinsing'] . ' ' . $model['intensive_wash'];
        }
        return $model['prewash'];
    }
//    if ($model['rinsing']) {
//        return $model['rinsing'];
//    }
//    if ($model['intensive_wash']) {
//        return $model['intensive_wash'];
//    }
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
//        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
</div>

<?php
    echo $columnFilterScript;
?>
