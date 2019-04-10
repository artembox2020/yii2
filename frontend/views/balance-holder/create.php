<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\BalanceHolder */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $company frontend\models\Company */

$this->title = Yii::t('frontend', 'Create Balance Holder');
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
<div class="balance-holder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'balanceHolders' => $balanceHolders,
        'company' => $company
    ]) ?>

</div>
