<?php

use \yii\base\DynamicModel;
use yii\widgets\Pjax;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>
                <?php Pjax::begin(); ?>

                <?php
                $model = new DynamicModel(['post_id', 'phrase_id']);
                $model
                    ->addRule(['post_id','phrase_id'], 'required')
                    ->addRule(['post_id', 'phrase_id'], 'integer')
                ;
                ?>

                <div class="phrase-bind-form">
                    <?php $form = ActiveForm::begin(['action' => '/backend/phrase/post-bind', 'options' => ['data-pjax' => true ]]); ?>
                    <?= $form->field($model, 'post_id')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'phrase_id')->textInput([]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'phrase-bind-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <?php Pjax::end(); ?>
