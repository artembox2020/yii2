<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use common\models\User;
use bs\Flatpickr\FlatpickrWidget;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */

$this->title = Yii::t('backend', 'Create user');
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('@frontend/views/net-manager/_button_back') ?>
</b><br>
<b>
    <?= $this->render('@frontend/views/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<div class="user-create">

    <?php $form = ActiveForm::begin(['id' => 'user-create-form']) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => $model->username]) ?>
    
    <?= $form->field($profile, 'firstname')->textInput(['maxlength' => $profile->firstname]) ?>
    
    <?= $form->field($profile, 'lastname')->textInput(['maxlength' => $profile->lastname]) ?>
    
    <?= $form->field($profile, 'position')->textInput(['maxlength' => $profile->position]) ?>
    
    <?= $form->field($profile, 'birthday')->widget(FlatpickrWidget::className(), [
        'locale' => strtolower(substr(Yii::$app->language, 0, 2)),
        'groupBtnShow' => true,
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'allowInput' => true,
            'defaultDate' => $profile->birthday ? date(DATE_ATOM, $profile->birthday) : null,
        ],
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->hiddenInput(['value' => User::STATUS_ACTIVE])->label(false); ?>

    <?= $form->field($model, 'roles')->checkboxList($roles) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Create'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>
