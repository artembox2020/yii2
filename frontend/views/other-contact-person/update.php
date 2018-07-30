<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\OtherContactPerson */
/* @var $balanceHolder frontend\models\BalanceHolder */

$this->title = Yii::t('frontend', 'Update Other Contact Person: {nameAttribute}', [
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
<div class="other-contact-person-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'balanceHolder' => $balanceHolder
    ]) ?>

</div>
