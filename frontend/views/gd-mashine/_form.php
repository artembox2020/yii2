<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\GdMashine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gd-mashine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imei_id')->hiddenInput(['value' => Yii::$app->request->post('imei_id')])->label(false); ?>

    <?= $form->field($model, 'type_mashine')->hiddenInput(['value' => 'GD'])->label(false); ?>

    <?= $form->field($model, 'serial_number')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
