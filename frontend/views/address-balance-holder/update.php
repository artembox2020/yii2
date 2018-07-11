<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $company frontend\models\Company */

$this->title = Yii::t('frontend', 'Update Address Balance Holder: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Address Balance Holders'), 'url' => ['/net-manager/addresses']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="address-balance-holder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'balanceHolder' => $balanceHolder,
        'company' => $company
    ]) ?>

</div>
