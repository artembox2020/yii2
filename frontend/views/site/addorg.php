<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserProfile;
use bs\Flatpickr\FlatpickrWidget;
use vova07\fileapi\Widget as FileApi;
?>
<div class="panel">
    <div class="panel-heading">
        <?=Yii::t('frontend', 'Add organization')?>
        <hr>
    </div>
    <div class="panel-body">
        <div id="nw-ress">
      
        </div>
        <div id="nw-add-f">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($org, 'name_org')->textInput(['maxlength' => true]) ?>

            <?= $form->field($org, 'desc')->textInput(['maxlength' => true]) ?>

                <?= $form->field($org, 'logo_path')->widget(FileApi::className(), [
                    'settings' => [
                        'url' => ['/site/fileapi-upload'],
                    ],
                    'crop' => true,
                    'cropResizeWidth' => 100,
                    'cropResizeHeight' => 100,
                ]) ?>
             <div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary']) ?>
			</div>
                <?php ActiveForm::end() ?>
        </div>    
    </div>
</div>