<?php
    use \yii\widgets\ActiveForm;
    use yii\helpers\Html;
    ?>

<div class="payment-default-index">
    <h1><?= Yii::t('payment', 'Payment') ?></h1>

    <div class="form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'card_no')->label(Yii::t('payment',  'card_no')) ?>

        <?= $form->field($model, 'amount')->label(Yii::t('payment', 'amount')) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
