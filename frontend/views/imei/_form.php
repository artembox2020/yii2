<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Imei */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imei-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imei')->textInput() ?>

    <?= $form->field($model, 'address_id')->hiddenInput(['value'=> Yii::$app->request->post('address_id')])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
