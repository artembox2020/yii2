<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use \yii\jui\AutoComplete;
use yii\web\JsExpression;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;

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
<b><?= Html::a(Yii::t('frontend', 'Add Address'), ['/address-balance-holder/create'], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?></b>
<br/>
<?php \yii\widgets\Pjax::begin(['id' => 'address-pjax-container']); ?>
<?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           'id',
           
            [
               'attribute' => 'balanceHolder.address',
               'label' => Yii::t('frontend','Balance Holder')
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
                    $entity = new Entity();
                    $addWashpay = $entity->AutoCompleteWidgetFilteredData([
                        'model' => $model,
                        'name' => 'imei',
                        'url' => '/net-manager/addresses-bind-to-imei',
                        'source' => $imeis,
                        'options' => [
                            'placeholder' => Yii::t('common', 'Type imei')
                        ]
                    ]);

                    return $entity->getUnitRelationData(
                        $model,
                        ['imei' => 'imei'],
                        $addWashpay
                    );
                }
            ],
    
            'countWashMachine',
            
            'countGelDispenser',
        ]
]); ?>
<?php \yii\widgets\Pjax::end(); ?>
