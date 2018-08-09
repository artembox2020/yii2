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
    if (!empty($addressBalanceHolder)) {
        $addressBalanceHolderId = $addressBalanceHolder->id; 
        $addressBalanceHolderOptions = [
            $addressBalanceHolderId => ['Selected' => true] 
        ];
    } else {
        if (!empty($imei->address_id)) {
            $addressBalanceHolderOptions = [
                $imei->address_id => ['Selected' => true] 
            ];
        } else {
            $addressBalanceHolderOptions = [];
        }
    }
?>
<div class="other-contact-person-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($imei, 'imei')->textInput(['maxlength' => true]) ?>

    <?= $form->field($imei, 'address_id')->dropDownList(
        $addresses,
        [
            'options' => $addressBalanceHolderOptions
        ]
    ) ?>

    <?= $form->field($imei, 'status')->label(Yii::t('frontend', 'Status'))->dropDownList(Imei::statuses()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
