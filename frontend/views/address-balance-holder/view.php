<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $imeis frontend\models\Imei */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Address Balance Holders'), 'url' => ['/net-manager/addresses']];
$this->params['breadcrumbs'][] = $this->title;
$dateFormat = "M j, Y";
?>
<div class="address-balance-holder-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            [
                'label' => Yii::t('frontend','Address'),
                'value' => $model->address
            ],
            
            [
                'label' => Yii::t('frontend','Balance Holder'),
                'value' => $model->balanceHolder->address
            ],
            
            [    
                'label' => Yii::t('frontend','Date Inserted'),
                'value' => date($dateFormat, $model->date_inserted)
            ],
            
            [
                'label' => Yii::t('frontend','Date Monitoring'),
                'value' => date($dateFormat, $model->date_connection_monitoring)
            ],
            
            'number_of_floors',
            
            'countWashMachine',
            
            'countGelDispenser',
            
        ],
    ])
?>

<h3 align="center"><?= Yii::t('frontend', 'Address Card') ?></h3>

<div><b><u><?= Yii::t('frontend','Summary Technical Data') ?></u></b></div>
<br/>

<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('frontend','Number Washine Cycles'),
                'value' => 400000000000
            ],
            
            [
                'label' => Yii::t('frontend','Average Currency Amount'),
                'value' => 350
            ],
            
            [    
                'label' => Yii::t('frontend','Money Amount'),
                'value' =>  8000000000
            ],
            
            [
                'label' => Yii::t('frontend','Last errors'),
                'value' => Yii::t('frontend','Last errors'),
            ],
        ],
    ])
?>

<div><b><u><?= Yii::t('frontend','Consolidated Financial Data') ?></u></b></div>
<br/>

</div>
