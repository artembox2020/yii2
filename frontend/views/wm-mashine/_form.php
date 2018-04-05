<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wm-mashine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imei_id')->textInput() ?>

    <?= $form->field($model, 'type_mashine')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number_device')->textInput() ?>

    <?= $form->field($model, 'level_signal')->textInput() ?>

    <?= $form->field($model, 'bill_cash')->textInput() ?>

    <?= $form->field($model, 'door_position')->textInput() ?>

    <?= $form->field($model, 'door_block_led')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'is_deleted')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
