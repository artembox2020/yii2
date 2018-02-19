<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Base */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imei')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gsmSignal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fvVer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numBills')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billAcceptorState')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_hard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'collection')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ZigBeeSig')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billCash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tariff')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'edate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billModem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sumBills')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numDev')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'devSignal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'statusDev')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'colGel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'colCart')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'timeout')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doorpos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doorled')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kpVer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'srVer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mTel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sTel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ksum')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
