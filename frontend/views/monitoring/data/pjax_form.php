<?php

    use yii\helpers\Html;
    use \yii\widgets\ActiveForm;

    $form = ActiveForm::begin(
        ['method' => 'post', 'options' => ['data-pjax' => true, 'class' => 'monitoring-pjax-form' ]]
    );
?>
    <div class="form-group">
        <?= Html::hiddenInput('serialNumber', ''); ?>
    </div>

    <div class="form-group">
        <?= Html::hiddenInput('addressId', ''); ?>
    </div>

    <div class="form-group">
        <?= Html::hiddenInput('sortOrder', $params['sortOrder']); ?>
    </div>

    <div class="form-group hidden">
        <?= Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']); ?>
    </div>

<?php 
    ActiveForm::end();
?>