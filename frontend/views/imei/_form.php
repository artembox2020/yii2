<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\BalanceHolder;
use frontend\services\custom\Debugger;
use frontend\models\Imei;

/* @var $this yii\web\View */
/* @var $model frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $form yii\widgets\ActiveForm */
/* @var $imei frontend\models\Imei */



// Debugger::dd($address);

?>

<div class="imei-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imei')->textInput() ?>

<!--    //$form->field($model, 'address_id')->hiddenInput(['value'=> Yii::$app->request->post('address_id')])->label(false);-->

    <?= $form->field($model, 'address_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($address, 'id', 'address')
    ) ?>
    
     <?= $form->field($model, 'status')->label(Yii::t('frontend', 'Status'))->dropDownList(Imei::statuses()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
