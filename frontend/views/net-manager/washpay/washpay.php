<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\components\responsive\DetailView;
use frontend\components\responsive\GridView;
use frontend\services\custom\Debugger;
use frontend\services\globals\EntityHelper;
use frontend\models\Imei;

/* @var $this yii\web\View */
/* @var $menu array */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\ImeiSearch */

?>
<?php
    $menu = [];
    define('UNKNOWN', Yii::t('common', 'Unknown'));
?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<b><?php if ( yii::$app->user->can('editTechData') ) echo Html::a(Yii::t('frontend', 'Add Washpay'), ['net-manager/washpay-create'], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?></b>
<br/>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
               'attribute' => 'id',
               'headerOptions' => [
                    'class' => 'narrow'
                ],
                'contentOptions' => function () {

                    return ['class' => 'narrow'];
                },
            ],
            [
                'attribute' => 'imei',
                'format' => 'raw',
                'value' => function($model) {

                    return Yii::$app->commonHelper->link($model);
                }
            ],
            [
               'attribute' => 'address',
               'format' => 'raw',
               'value' => function($model) use($addresses) {
                    $entityHelper = new EntityHelper();
                    $addAddress = $entityHelper->AutoCompleteWidgetFilteredData([
                        'model' => $model,
                        'name' => 'address',
                        'url' => '/net-manager/washpay-bind-to-address',
                        'source' => $addresses,
                        'options' => [
                            'placeholder' => Yii::t('common', 'Type address')
                        ]
                    ]);

                    $relationData = $model->tryRelationData(
                        ['address' => ['address', 'floor'], ', ']
                    );

                    if ($relationData) {

                        return Yii::$app->commonHelper->link($model->address, [], $relationData);
                    }

                    return $addAddress;
                },
            ],
           
            [
               'attribute' => 'balanceHolder.name',
               'label' => Yii::t('frontend', 'Balance Holder'),
               'format' => 'raw',
               'value' => function ($model) {

                    return Yii::$app->commonHelper->link($model->balanceHolder);
               }
            ],
           
            [
                'attribute' => 'last_ping',
                'format' => 'raw',
                'label' => Yii::t('frontend', 'Last ping'),
                'value' => function($model) {

                    return $model->getLastPing();
                },
            ]
        ]
]); ?>

<p><u><b><?= Yii::t('frontend','General Info') ?></b></u><p/>

<!-- Summary by models -->
<?php ob_start(); ?>
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' =>  Yii::t('frontend', 'WP-3'),
                'value' => UNKNOWN
            ],
            [
                'label' =>  Yii::t('frontend', 'WP-4'),
                'value' => UNKNOWN
            ],
        ]
    ]);
?>
<?php $modelSummary = ob_get_clean(); ?>

<!-- Summary by the date of production -->
<?php ob_start(); ?>
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' =>  Yii::t('frontend', 'Up-To 1 year'),
                'value' => UNKNOWN
            ],
            [
                'label' =>  Yii::t('frontend', 'Up-To 2 years'),
                'value' => UNKNOWN
            ],
            [
                'label' =>  Yii::t('frontend', 'Up-To 3 years'),
                'value' => UNKNOWN
            ],
            [
                'label' =>  Yii::t('frontend', 'Up-To 4 years'),
                'value' => UNKNOWN
            ],
            [
                'label' =>  Yii::t('frontend', 'Up-To 5 years'),
                'value' => UNKNOWN
            ],
            [
                'label' =>  Yii::t('frontend', 'Above 5 years'),
                'value' => UNKNOWN
            ],
        ]
    ]);
?>
<?php $dateProductionSummary = ob_get_clean(); ?>

<!-- Summary by status -->
<?php ob_start(); ?>
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('frontend', 'Actual Ping'),
                'value' => $model->getActualPingCount()
            ],
            [
                'label' => Yii::t('frontend', 'Not Actual Ping'),
                'value' => $model->getNotInitializedCount()
            ],
            [
                'label' =>  Yii::t('frontend', 'Status Off'),
                'value' => $model->getCountByStatus(Imei::STATUS_OFF)
            ],
        ]
    ]);
?>
<?php $statusSummary = ob_get_clean(); ?>

<!-- Summary by location -->
<?php ob_start(); ?>
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('frontend', 'On Point/At Work'),
                'value' => $model->getCountBystatus(Imei::STATUS_ACTIVE)
            ],
            [
                'label' => Yii::t('frontend', 'In Stock/Sale'),
                'value' => UNKNOWN
            ],
            [
                'label' => Yii::t('frontend', 'In Stock/Repair'),
                'value' => $model->getCountBystatus(Imei::STATUS_UNDER_REPAIR)
            ],
            [
                'label' => Yii::t('frontend', 'Junk'),
                'value' => $model->getCountBystatus(Imei::STATUS_JUNK)
            ],
        ]
    ]);
?>
<?php $locationSummary = ob_get_clean(); ?>

<!-- Main Detail View -->
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('frontend', 'General Count'),
                'value' => $model->getGeneralCount()
            ],
            [
                'label' =>  Yii::t('frontend', 'By Models'),
                'format' => 'raw',
                'value' => $modelSummary
            ],
            [
                'label' =>  Yii::t('frontend', 'By Date Production'),
                'format' => 'raw',
                'value' => $dateProductionSummary
            ],
            [
                'label' =>  Yii::t('frontend', 'By Status'),
                'format' => 'raw',
                'value' => $statusSummary
            ],
            [
                'label' =>  Yii::t('frontend', 'By Location'),
                'format' => 'raw',
                'value' => $locationSummary
            ],
        ]
    ]);
?>
