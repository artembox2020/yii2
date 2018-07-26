<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\BalanceHolder */
/* @var $balanceHolders */

$this->title = Yii::t('frontend', 'Update Balance Holder: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Balance Holders'), 'url' => ['/net-manager/balance-holders']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/net-manager/view-balance-holder', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br>
<div class="balance-holder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'balanceHolders' => $balanceHolders,
    ]) ?>

</div>
