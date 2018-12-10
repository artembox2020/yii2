<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\WmMashine;
use bs\Flatpickr\FlatpickrWidget;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model frontend\models\WmMashine */
/* @var $addresses frontend\models\AddressBalanceHolder */

?>

<div class="other-contact-person-form">
    <h3><u><?= Yii::t('frontend', 'Create Wash Machine') ?></u></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number_device')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inventory_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_install')->widget(FlatpickrWidget::className(), [
        'locale' => strtolower(substr(Yii::$app->language, 0, 2)),
        'groupBtnShow' => true,
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'allowInput' => true,
            'defaultDate' => $model->date_install ? date(DATE_ATOM, $model->date_install) : null,
        ],
    ]) ?>

    <?= $form->field($model, 'date_build')->widget(FlatpickrWidget::className(), [
        'locale' => strtolower(substr(Yii::$app->language, 0, 2)),
        'groupBtnShow' => true,
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'allowInput' => true,
            'defaultDate' => $model->date_build ? date(DATE_ATOM, $model->date_build) : null,
        ],
    ]) ?>

    <?= $form->field($model, 'date_purchase')->widget(FlatpickrWidget::className(), [
        'locale' => strtolower(substr(Yii::$app->language, 0, 2)),
        'groupBtnShow' => true,
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'allowInput' => true,
            'defaultDate' => $model->date_purchase ? date(DATE_ATOM, $model->date_purchase) : null,
        ],
    ]) ?>

    <?= $form->field($model, 'date_connection_monitoring')->widget(FlatpickrWidget::className(), [
        'locale' => strtolower(substr(Yii::$app->language, 0, 2)),
        'groupBtnShow' => true,
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'allowInput' => true,
            'defaultDate' => $model->date_connection_monitoring ? date(DATE_ATOM, $model->date_connection_monitoring) : null,
        ],
    ]) ?>

    <?= $form->field($model, 'status')->label(Yii::t('frontend', 'Status'))->radioList(WmMashine::statuses()) ?>
    <?= $form->field($model, 'imei_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($imeis, 'id',
        function($model) {
            return $model->imei . ' #' . $model->address->name;
        })
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
