<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descr')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'published_at')->widget(DateTimePicker::className(),[
    'name' => 'dp_1',
    'type' => DateTimePicker::TYPE_INPUT,
    'options' => ['placeholder' => 'Ввод даты/времени...'],
    'convertFormat' => true,
    'value'=> date("d.m.Y h:i",(integer) $model->published_at),
    'pluginOptions' => [
    'format' => 'dd.MM.yyyy hh:i',
    'autoclose'=>true,
    'weekStart'=>1, //неделя начинается с понедельника
    'startDate' => '01.05.2015 00:00', //самая ранняя возможная дата
    'todayBtn'=>true, //снизу кнопка "сегодня"
    ]
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
