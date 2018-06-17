<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\WmMashine;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $company frontend\models\Company */
/* @var $model frontend\models\WmMashine */
/* @var $address frontend\models\AddressBalanceHolder */
/* @var $addresses frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $balanceHolders frontend\models\BalanceHolder */
/* @var $imei frontend\models\Imei */
/* @var $imeis frontend\models\Imei */

?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-info alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i><?= Yii::t('frontend','Info') ?></h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>
<div class="other-contact-person-form">
    <h3><u>Add WM Machine</u></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number_device')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_id')->hiddenInput(['value'=> $company->id])->label(false); ?>

    <?= $form->field($model, 'status')->label(Yii::t('frontend', 'Status'))->radioList(WmMashine::statuses()) ?>

    <?= $form->field($model, 'imei_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($imeis, 'id', 'imei')
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
