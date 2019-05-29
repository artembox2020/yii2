<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use \yii\jui\AutoComplete;
use yii\web\JsExpression;
use frontend\services\custom\Debugger;
use frontend\services\globals\EntityHelper;

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
<b><?php if ( yii::$app->user->can('editFinData') ) echo Html::a(Yii::t('frontend', 'Add Address'), ['/address-balance-holder/create'], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?></b>
<br/>
<?php \yii\widgets\Pjax::begin(['id' => 'address-pjax-container']); ?>
<?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           'id',
           
            [
               'attribute' => 'balanceHolder.name',
               'label' => Yii::t('frontend','Balance Holder')
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('frontend','Short Address')
            ],
           
            [
                'attribute' => 'address',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(
                        $model->address,
                        ['/address-balance-holder/view', 'id' => $model->id]
                    );
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
                    
                    return $relationData ? $relationData : $addWashpay;
                }
            ],
    
            'countWashMachine',
            
            'countGelDispenser',
        ]
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
