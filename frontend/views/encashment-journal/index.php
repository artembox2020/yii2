<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \frontend\models\AddressBalanceHolder;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CbLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="address-balance-holder-index logs-index encashment-index">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'banknote_nominals',
            'label' => '',
            'format' => 'raw',
            'value' => function($model) use ($searchModel, $dataProvider) {

                return $searchModel->getBanknoteFaceValuesView($model, $dataProvider);
            },
            'contentOptions' => ['class' => 'banknote-nominals-cell']
        ],
        [
            'attribute' => 'unix_time_offset',
            'format' => 'raw',
            'label' => Yii::t('frontend', 'Hour that date'),
            'value' => function($model) use ($searchModel, $params)
            {

                return date("d.m.Y", $model['unix_time_offset']);
            },
            'filter' => $this->render(
                '/journal/filters/main',
                [
                    'name'=> 'unix_time_offset',
                    'params' => $params, 
                    'searchModel' => $searchModel,
                    'sortType' => $searchFilter->getSortType($params, 'unix_time_offset')
                ]),
        ],
        [
            'attribute' => 'unix_time_offset_time',
            'label' => Yii::t('frontend', 'Time'),
            'value' => function($model) use ($searchModel)
            {

                return date("H:i", $model['unix_time_offset']);
            },
        ],
        [
            'attribute' => 'address',
            'label' => Yii::t('frontend', 'Address'),
            'value'=> function ($model) use($searchModel) {

                return $searchModel->getAddressView($model);
            },
            'filter' => $this->render(
                '/journal/filters/main',
                [
                    'name'=> 'address',
                    'params' => $params,
                    'sortType' => $searchFilter->getSortType($params, 'address')
                ]
            ),
        ],
        [
            'attribute' => 'encashment',
            'label' => Yii::t('logs', 'Encashment'),
            'format' => 'raw',
            'value'=> function ($model) use ($searchModel) {

                return $searchModel->getCollectionCounterView($model);
            },
        ],
        [
            'attribute' => 'last_encashment_days_before',
            'label' => Yii::t('logs', 'Last Encashment Days Before'),
            'value'=> function ($model) use ($searchModel) {

                return $searchModel->getLastCollectionDaysBefore($model);
            },
        ],
        [
            'attribute' => 'fireproof_counter_hrn',
            'label' => Yii::t('logs', 'Fireproof Counter'),
            'value'=> function ($model) use($searchModel) {

                return $searchModel->getFireproofCounterHrn($model);
            },
        ],
        [
            'attribute' => 'last_fireproof_counter_hrn',
            'label' => Yii::t('logs', 'Last Fireproof Counter'),
            'value'=> function ($model) use($searchModel) {

                return $searchModel->getLastFireproofCounterHrn($model);
            },
        ],
        /*[
            'attribute' => 'last_collection_counter',
            'format' => 'raw',
            'label' => Yii::t('logs', 'Last Collection Counter'),
            'value' => function($model) use ($searchModel)
            {

                return $searchModel->getLastCollectionCounter($model);
            }
        ],*/
        [
            'attribute' => 'recount_amount',
            'format' => 'raw',
            'label' => Yii::t('logs', 'Recount Amount'),
            'value' => function($model) use ($searchModel)
            {

                return $searchModel->getRecountAmount($model);
            }
        ],
        [
            'attribute' => 'difference',
            'format' => 'raw',
            'label' => Yii::t('logs', 'Difference'),
            'value' => function($model) use ($searchModel)
            {

                return $searchModel->getDifferenceView($model);
            },
            'contentOptions' => ['class' => 'cell-difference']
        ]
    ],
]); ?>
</div>

<?= $recountAmountScript ?>
<?= $script ?>
<?= $submitFormOnInputEvents; ?>
<?= $removeRedundantGrids; ?>