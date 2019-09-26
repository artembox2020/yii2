<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\components\responsive\DetailView;
use \yii\jui\AutoComplete;
use yii\web\JsExpression;
use frontend\services\custom\Debugger;
use frontend\services\globals\EntityHelper;
use frontend\components\responsive\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
/* @var $addresses */
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<b><?php if ( yii::$app->user->can('editTechData') ) echo Html::a(Yii::t('frontend', 'Add Address'), ['/address-balance-holder/create'], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?></b>
<br/>
<?php \yii\widgets\Pjax::begin(['id' => 'address-pjax-container']); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
               'attribute' => 'id',
               'headerOptions' => [
                    'class' => 'narrow'
                ],
                'contentOptions' =>function () {

                    return ['class' => 'narrow'];
                },
            ],
            [
               'attribute' => 'balanceHolder.name',
               'label' => Yii::t('frontend','Balance Holder'),
               'format' => 'raw',
               'value' => function($model) {

                    return Yii::$app->commonHelper->link($model->balanceHolder);
                },
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('frontend','Short Address')
            ],
           
            [
                'attribute' => 'address',
                'format' => 'raw',
                'value' => function($model) {

                    return Yii::$app->commonHelper->link($model);
                }
            ],
            
            [
                'attribute' => 'floor',
            ],
            
            [
                'attribute' => 'imei',
                'label' => Yii::t('frontend', 'Imei'),
                'format' => 'raw',
                'value' => function($model) use($imeis) {
                    $entityHelper = new EntityHelper();
                    $addWashpay = $entityHelper->AutoCompleteWidgetFilteredData([
                        'model' => $model,
                        'name' => 'imei',
                        'url' => '/net-manager/addresses-bind-to-imei',
                        'source' => $imeis,
                        'options' => [
                            'placeholder' => Yii::t('common', 'Type imei')
                        ]
                    ]);

                    $relationData = $entityHelper->tryUnitRelationData(
                        $model,
                        ['imei' => 'imei']
                    );

                    return $relationData ? Yii::$app->commonHelper->link($model->imei, [], $relationData) : $addWashpay;
                }
            ],
    
            'countWashMachine',
            
            'countGelDispenser',
        ],
        'gridClass' => GridView::OPTIONS_DEFAULT_GRID_CLASS.' grid-addresses'
]); ?>
<?php \yii\widgets\Pjax::end(); ?>
<p><u><b><?= Yii::t('frontend','General Info') ?></b></u><p/>
<?= DetailView::widget([
    'model' => $company,
    'attributes' => [
        [
            'label' =>  Yii::t('frontend', 'Count Addresses'),
            'value' => $company->getCountAddress()
        ],
    ]
]);
