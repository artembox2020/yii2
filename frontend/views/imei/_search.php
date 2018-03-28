<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ImeiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imei-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'imei') ?>

    <?= $form->field($model, 'address_id') ?>

    <?= $form->field($model, 'type_packet') ?>

    <?= $form->field($model, 'imei_central_board') ?>

    <?php // echo $form->field($model, 'firmware_version') ?>

    <?php // echo $form->field($model, 'type_bill_acceptance') ?>

    <?php // echo $form->field($model, 'serial_number_kp') ?>

    <?php // echo $form->field($model, 'phone_module_number') ?>

    <?php // echo $form->field($model, 'crash_event_sms') ?>

    <?php // echo $form->field($model, 'critical_amount') ?>

    <?php // echo $form->field($model, 'time_out') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('frontend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
