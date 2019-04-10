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
<div class="address-balance-holder-index logs-index">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => "<b>".Yii::t('frontend', 'Shown')."</b> {begin} - {end} ".Yii::t('frontend', 'From')." {$itemsCount}",
    'columns' => [
        [
            'attribute' => 'unix_time_offset',
            'label' => Yii::t('frontend', 'Hour that date'),
            'value' => function($model)
            {

                return date('d.m.Y H:i:s', $model['unix_time_offset']);
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'date', 'params' => $params, 'searchModel' => $searchModel]),
        ],
        [
            'attribute' => 'address',
            'label' => Yii::t('frontend', 'Address'),
            'value'=> function ($model) use($searchModel) {

                return $searchModel->getAddressView($model);
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
        ],
        [
            'attribute' => 'device',
            'label' => Yii::t('logs', 'Device'),
            'value'=> function ($model) use ($searchModel) {

                return $searchModel->getDeviceView($model);
            },
            'filter' => $this->render('/journal/filters/main', ['name'=> 'number', 'params' => $params]),
        ],
        [
            'attribute' => 'signal',
            'label' => Yii::t('logs', 'Signal level/Rate'),
            'value' => function ($model) use ($searchModel) {

                return $searchModel->getLevelSignalView($model);
            }
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'label' => Yii::t('logs', 'Event'),
            'value'=> function ($model) use ($searchModel) {

                return $searchModel->getStatus($model);
            },
            'filter' => false,
            'contentOptions' => ['class' => 'aggregated-status-info']
        ],
        [
            'attribute' => 'price',
            'label' => Yii::t('logs', 'Rate, uah'),
            'value' => function ($model) use ($searchModel) {

                return $searchModel->getPrice($model);
            },
        ],
        [
            'attribute' => 'account_money',
            'label' => Yii::t('logs', 'Account Money'),
            'value' => function ($model) use ($searchModel) {

                return $searchModel->getAccountMoney($model);
            },
        ],
        [
            'attribute' => 'notes_biliards_pcs',
            'label' => Yii::t('logs', 'Bonds in bills/Washing Mode'),
            'value'=> function ($model) use($searchModel) {

                return $searchModel->getNotesBilliardsPcs($model);
            },
        ],
        [
            'attribute' => 'fireproof_counter_hrn',
            'label' => Yii::t('logs', 'Fireproof Counter/Temperature'),
            'value'=> function ($model) use($searchModel) {

                return $searchModel->getFireproofCounterHrn($model);
            },
        ],
        [
            'attribute' => 'collection_counter',
            'label' => Yii::t('logs', 'Collection Counter/Spin Type'),
            'value'=> function ($model) use ($searchModel) {

                return $searchModel->getCollectionCounter($model);
            },
        ],
        [
            'attribute' => 'last_collection_counter',
            'label' => Yii::t('logs', 'Last Collection Counter/Additional Options'),
            'value' => function($model) use ($searchModel)
            {

                return $searchModel->getLastCollectionCounter($model);
            }
        ]
    ],
]); ?>
</div>