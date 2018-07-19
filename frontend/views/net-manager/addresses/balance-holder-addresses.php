<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
/* @var $addresses */
?>
<b><?= Html::a(Yii::t('frontend', 'Add Address'), ['/address-balance-holder/create', 'balanceHolderId' => $model->id], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?></b>
<br/>
<?php \yii\widgets\Pjax::begin(['id' => 'address-pjax-container']); ?>
<?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           'id',
           
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
                'value' => function($model) {
                    $addWashpay = Html::a(
                        Yii::t('frontend', 'Add Washpay'), 
                        ['/net-manager/washpay-create', 'addressBalanceHolderId' => $model->id], 
                        ['class' => 'btn btn-success', 'style' => 'color: #fff;']
                    );
                    return !empty($model->imei) ? $model->imei->imei : $addWashpay;
                }
            ],
    
            'countWashMachine',
            
            'countGelDispenser',
        ]
]); ?>
<?php \yii\widgets\Pjax::end(); ?>
