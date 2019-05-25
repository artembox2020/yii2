<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;

/* @var $random integer */
/* @var $params array */
/* @var $start timestamp */
/* @var $end integer */
?>
<div class="d<?= $random ?>">
<?php    
    $form = ActiveForm::begin(
        ['method' => 'post', 'options' => ['data-pjax' => true, 'data-id' => $random, 'class' => "summary-pjax-form c$random" ]]
    );
?>
    <div class="form-group">
        <?= Html::hiddenInput('imeiId', $params['imei_id']); ?>
    </div>

    <div class="form-group">
        <?= Html::hiddenInput('addressId', $params['address_id']); ?>
    </div>

    <div class="form-group">
        <?= Html::hiddenInput('isCancelled', $params['is_cancelled']); ?>
    </div>

    <div class="form-group">
        <?= Html::hiddenInput('start', $start); ?>
    </div>

    <div class="form-group">
        <?= Html::hiddenInput('end', $end); ?>
    </div>

    <div class="form-group hidden">
        <?= Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']); ?>
    </div>
<?php 
    ActiveForm::end();
?>
</div>