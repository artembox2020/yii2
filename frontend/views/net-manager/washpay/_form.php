<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Imei;
use frontend\models\AddressBalanceHolder;
use frontend\services\globals\Entity;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $imei frontend\models\Imei */
/* @var $addresses frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $balanceHolders frontend\models\BalanceHolder */
?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-info alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i><?= Yii::t('frontend','Info') ?></h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>
<?php
    // set default address and balanceHolder options
    if(!empty($addressBalanceHolderId)) {
        $entity = new Entity();
        $addressBalanceHolder = $entity->getUnitPertainCompany($addressBalanceHolderId, new AddressBalanceHolder());
        $defaultAddressBalanceHolderId = $addressBalanceHolder->id;
        $defaultBalanceHolderId = $addressBalanceHolder->balanceHolder->id;
        
        $addressBalanceHolderOptions = [
            $defaultAddressBalanceHolderId => ['Selected' => true] 
        ];
        
        $balanceHolderOptions = [ 
            $defaultBalanceHolderId => ['Selected' => true] 
        ];
    }
    else {
        $addressBalanceHolderOptions = [];
        $balanceHolderOptions = [];
    }
?>
<div class="other-contact-person-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($imei, 'imei')->textInput(['maxlength' => true]) ?>

    <?= $form->field($imei, 'imei_central_board')->textInput(['maxlength' => true]) ?>

    <?= $form->field($imei, 'type_packet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($imei, 'firmware_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($imei, 'type_bill_acceptance')->textInput(['maxlength' => true]) ?>

    <?= $form->field($imei, 'serial_number_kp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($imei, 'address_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($addresses, 'id', 'address'),
        [
            'options' => $addressBalanceHolderOptions
        ]
    ) ?>

    <?= $form->field($imei, 'balance_holder_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($balanceHolders, 'id', 'name'),
        [
            'options' => $balanceHolderOptions
        ]
    ) ?>

    <?= $form->field($imei, 'status')->label(Yii::t('frontend', 'Status'))->radioList(Imei::statuses()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
