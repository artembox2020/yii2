<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\services\custom\Debugger;

/* @var $this yii\web\View */
/* @var $model frontend\models\BalanceHolder */
/* @var $form yii\widgets\ActiveForm */

foreach ($balanceHolders as $company) {
    $id = $company->company_id;
}

?>

<div class="balance-holder-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_start_cooperation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_connection_monitoring')->textInput(['maxlength' => true]) ?>

    <?=  Html::hiddenInput('company_id', $company->id); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
