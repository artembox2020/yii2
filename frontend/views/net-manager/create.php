<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use common\models\User;
use common\models\UserProfile;
use bs\Flatpickr\FlatpickrWidget;
use vova07\fileapi\Widget as FileApi;

/* @var $this yii\web\View */
/* @var $profile common\models\UserProfile */
/* @var $user backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */
/* @var $user common\models\User */

$this->title = Yii::t('backend', 'Update user: {username}', ['username' => $user->username]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="user-update">

    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'roles')->checkboxList($roles) ?>
    
    <?= $form->field($user, 'status')->hiddenInput(['value' => User::STATUS_ACTIVE])->label(false); ?>
    
    <?= $form->field($profile, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($profile, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($profile,'position')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($profile, 'avatar_path')->widget(FileApi::className(), [
        'settings' => [
            'url' => ['/site/fileapi-upload'],
        ],
        'crop' => true,
        'cropResizeWidth' => 100,
        'cropResizeHeight' => 100,
    ]) ?>

    <?= $form->field($profile, 'gender')->dropDownlist(
        [
            UserProfile::GENDER_MALE => Yii::t('backend', 'Male'),
            UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
        ],
        ['prompt' => '']
    ) ?>

    <?= $form->field($profile, 'other')->textarea(['rows' => 6, 'maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>
