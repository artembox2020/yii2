<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders frontend\models\BalanceHolder */
/* @var $addresses */
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<b><?= Html::a(Yii::t('frontend', 'Add Washpay'), ['net-manager/washpay-create'], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?></b>
<br/>
<?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           'id',
           
           [
                'attribute' => 'imei',
                'format' => 'raw',
                'value' => function($model) {
                    
                    return Html::a(
                        $model->imei,
                        ['/imei/view', 'id' => $model->id]
                    );
                }
           ],
           
           [
               'attribute' => 'address',
               'value' => function($model) {
                   
                   if(!empty($model->address))
                   
                       return $model->address->address;
                   else
                   
                       return Yii::t('common', 'Not Set');       
               },
           ],
           
           [
               'attribute' => 'balanceHolder.address',
               'label' => Yii::t('frontend', 'Balance Holder'),
           ],
           
           [
                'attribute' => 'last_ping',
                'label' => Yii::t('frontend', 'Last ping'),
                'value' => function($model) {
                    $getInitResult = $model->getInit();
                    if($getInitResult == 'Ok')
                    
                        return date('dd.MM.yyyy H:i:s', $model->updated_at);
                    else
                    
                        return $getInitResult;
                },
           ]
        ]
]); ?>
