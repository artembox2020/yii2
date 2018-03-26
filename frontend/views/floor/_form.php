<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Floor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="floor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'floor_number')->textInput() ?>

    <?= $form->field($model, 'address_balance_holder_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'is_deleted')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
