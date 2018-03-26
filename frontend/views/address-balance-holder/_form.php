<?php

use common\models\User;
use frontend\models\BalanceHolder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AddressBalanceHolder */
/* @var $balanceHolders frontend\models\BalanceHolder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-balance-holder-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'balance_holder_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($balanceHolders, 'id', 'name')
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
