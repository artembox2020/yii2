<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

?>
<?php Pjax::begin(); ?>

<?php $model = new \common\models\PhraseForm(); ?>

<span class="glyphicon glyphicon-plus phrase-create" aria-hidden="true">
    <label>Додати фразу</label>
</span>

<div class="phrase-form invisible">
    <?php $form = ActiveForm::begin(['action' => '/backend/phrase/index', 'options' => ['data-pjax' => true ]]); ?>
    <?= $form->field($model, 'phrase')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'lang') ?>
    <?= $form->field($model, 'trans') ?>
    <?= $form->field($model, 'transLang')->textInput([]) ?>
    <span class="glyphicon glyphicon-remove phrase-remove"></span>
    <br/>
    <br/>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'phrase-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>

<?php $this->registerCss(
<<<CSS
    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .phrase-create *, .phrase-form .phrase-remove {
        cursor: pointer;
    }
    .phrase-form .form-group {
        display: inline-block;
    }
    .phrase-form .form-group .invalid-feedback {
        position: absolute;
    }
CSS
);
?>

<?php $this->registerJS(
<<<JS
jQuery(function($) {
    $('body').on('click', '.phrase-create', function() {
        $(this).addClass("disabled");
        let form = document.querySelector(".phrase-form");
        fadeInEffect(form);
    });

    $("body").on("click", ".phrase-remove", function() {
        fadeOutEffect(this.closest(".phrase-form"));
        $(".phrase-create").removeClass('disabled');
    });
});
JS
);