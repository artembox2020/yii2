<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ImeiData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imei-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imei_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'imei')->textInput() ?>

    <?= $form->field($model, 'level_signal')->textInput() ?>

    <?= $form->field($model, 'on_modem_account')->textInput() ?>

    <?= $form->field($model, 'in_banknotes')->textInput() ?>

    <?= $form->field($model, 'money_in_banknotes')->textInput() ?>

    <?= $form->field($model, 'fireproof_residue')->textInput() ?>

    <?= $form->field($model, 'price_regim')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'is_deleted')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
