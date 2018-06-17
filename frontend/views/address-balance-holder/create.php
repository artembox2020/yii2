<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $company frontend\models\Company */

$this->title = Yii::t('frontend', 'Create Address Balance Holder');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Address Balance Holders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-balance-holder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'balanceHolder' => $balanceHolder,
        'company' => $company
    ]) ?>

</div>
