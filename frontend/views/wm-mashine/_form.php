<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wm-mashine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imei_id')->hiddenInput(['value'=> Yii::$app->request->post('imei_id')])->label(false); ?>

    <?= $form->field($model, 'number_device')->textInput() ?>

    <?= $form->field($model, 'serial_number')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
