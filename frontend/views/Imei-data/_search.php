<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ImeiDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imei-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'imei_id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'imei') ?>

    <?= $form->field($model, 'level_signal') ?>

    <?php // echo $form->field($model, 'on_modem_account') ?>

    <?php // echo $form->field($model, 'in_banknotes') ?>

    <?php // echo $form->field($model, 'money_in_banknotes') ?>

    <?php // echo $form->field($model, 'fireproof_residue') ?>

    <?php // echo $form->field($model, 'price_regim') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('frontend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
