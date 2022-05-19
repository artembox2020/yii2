<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>
<div id="<?= $id ?>" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= $title ?></h4>
            </div>
            <div class="modal-body">
                <div class="<?= $formClass ?>">
                <?php $model->load(Yii::$app->request->queryParams); ?>
                <?php $form = ActiveForm::begin(['method' => $method, 'options' => ['data-pjax' => $isAjax ]]); ?>
                <?php foreach ($modelColumns as $column): ?>
                    <?= $form->field($model, $column)->textInput() ?>
                <?php endforeach; ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => "<?= $formClass ?>-button"]) ?>
                </div>
                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
