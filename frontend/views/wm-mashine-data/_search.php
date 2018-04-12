<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashineDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wm-mashine-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'wm_mashine_id') ?>

    <?= $form->field($model, 'type_mashine') ?>

    <?= $form->field($model, 'number_device') ?>

    <?= $form->field($model, 'level_signal') ?>

    <?php // echo $form->field($model, 'bill_cash') ?>

    <?php // echo $form->field($model, 'door_position') ?>

    <?php // echo $form->field($model, 'door_block_led') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('frontend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
