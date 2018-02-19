<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Base;
/* @var $this yii\web\View */
/* @var $model frontend\models\BaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	<?php var_dump(Base::get_adress('Борщагівська, 148'));?>
	<?php // echo $form->field($model, 'gender')->dropDownlist([], ['prompt' => '']) ?>
    <?php // echo $form->field($model, 'numBills') ?>

    <?php // echo $form->field($model, 'billAcceptorState') ?>

    <?php // echo $form->field($model, 'id_hard') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'collection') ?>

    <?php // echo $form->field($model, 'ZigBeeSig') ?>

    <?php // echo $form->field($model, 'billCash') ?>

    <?php // echo $form->field($model, 'tariff') ?>

    <?php // echo $form->field($model, 'event') ?>

    <?php // echo $form->field($model, 'edate') ?>

    <?php // echo $form->field($model, 'billModem') ?>

    <?php // echo $form->field($model, 'sumBills') ?>

    <?php // echo $form->field($model, 'ost') ?>

    <?php // echo $form->field($model, 'numDev') ?>

    <?php // echo $form->field($model, 'devSignal') ?>

    <?php // echo $form->field($model, 'statusDev') ?>

    <?php // echo $form->field($model, 'colGel') ?>

    <?php // echo $form->field($model, 'colCart') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'timeout') ?>

    <?php // echo $form->field($model, 'doorpos') ?>

    <?php // echo $form->field($model, 'doorled') ?>

    <?php // echo $form->field($model, 'kpVer') ?>

    <?php // echo $form->field($model, 'srVer') ?>

    <?php // echo $form->field($model, 'mTel') ?>

    <?php // echo $form->field($model, 'sTel') ?>

    <?php // echo $form->field($model, 'ksum') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
