<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
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
            
            'floor',
            
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
                    return count($model->imeis) ? $model->imeis[0]->imei : $addWashpay;
                }
            ],
    
            'countWashMachine',
            
            'countGelDispenser',
        ]
]); ?>
