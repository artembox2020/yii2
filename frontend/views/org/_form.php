<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bs\Flatpickr\FlatpickrWidget;
use vova07\fileapi\Widget as FileApi;
/* @var $this yii\web\View */
/* @var $model frontend\models\Org */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_org')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'logo_path')->widget(FileApi::className(), [
                    'settings' => [
                        'url' => ['/site/fileapi-upload'],
                    ],
                    'crop' => true,
                    'cropResizeWidth' => 100,
                    'cropResizeHeight' => 100,
                ]) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
	
	<?= $form->field($model, 'user_id')->dropDownList(
				\yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username')
	) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
