<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $company frontend\models\Company */

$this->title = Yii::t('frontend', 'Update Address Balance Holder: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_button_back') ?>
</b><br>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br>
<div class="address-balance-holder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'balanceHolders' => $balanceHolders,
        'company' => $company
    ]) ?>

</div>
