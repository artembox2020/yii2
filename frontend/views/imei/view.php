<?php

use yii\helpers\Html;
use \frontend\models\Imei;
use frontend\services\globals\Entity;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Imei */

$this->title = $model->imei;
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
 <p><u><b><?= Yii::t('frontend','WashPay Card').'-'.$model->id ?></b></u><p/>
<div class="imei-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Update'), ['/net-manager/washpay-update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        
        'attributes' => [
            'imei',
            
            'phone_module_number',
            
            [
                'attribute' => 'balanceHolder',
                'value' => function($model) {
                    
                    if (!empty($model->balanceHolder)) {
                        return $model->balanceHolder->name;
                    } else {
                        return Yii::t('common', 'Not Set');
                    }
                }
            ],
            
            [
                'attribute' => 'address',
                'value' => function($model) {
                    $entity = new Entity();

                    return $model->getRelationData(
                        ['address' => 'address'],
                        Yii::t('common', 'Not Set')
                    );
                }
            ],
            
            'imei_central_board',
            
            'firmware_version',
            
            [
                'attribute' => 'last_ping',
                'label' => Yii::t('frontend', 'Last ping'),
                'value' => function($model) {
                    
                    return $model->getLastPing();
                }
            ]
        ],
    ]) ?>
    
    <p><u><b><?= Yii::t('frontend','History') ?></b></u><p/>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' =>  Yii::t('frontend', 'Number Washine Cycles'),
                'value' => 23454
            ],
            
            [
                'label' =>  Yii::t('frontend', 'Time Work'),
                'value' => 346567
            ],
            
            [
                'label' =>  Yii::t('frontend', 'Money Amount'),
                'value' => 45665
            ],
            
            [
                'label' => Yii::t('frontend', 'Last errors'),
                'value' => Yii::t('frontend', 'Last errors'),
            ],
            
        ]
    ]); ?>

</div>
