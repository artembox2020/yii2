<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use common\models\User;
use common\models\UserProfile;
use bs\Flatpickr\FlatpickrWidget;
use vova07\fileapi\Widget as FileApi;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $profile common\models\UserProfile */
/* @var $roles array */

?>

<?php
    $form = ActiveForm::begin([
        'id' => 'user-edit-form',
        'action' => '/net-manager/edit-employee?id='.(!empty($_GET['id']) ? $_GET['id'] : ($_GET['userId'] ?? '')),
        'method' => 'post'
    ])
?>
<div 
    class="modal fade"
    id="editcoworker"
    tabindex="-1"
    role="dialog"
    aria-labelledby="create-user"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header flex-column">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= Yii::t('common', 'Close') ?>">
                    <svg height="32" class="octicon octicon-x" viewBox="0 0 12 16" version="1.1" width="24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48L7.48 8z"></path>
                    </svg>
                </button>
                <h3 class="modal-title fw600" id="edit-user">
                    <?= Yii::t('backend', 'Update user') ?>
                </h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group flex-column">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-md-6 flex-column">
                            <?= $form->field($profile,'position')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-lg-6 form-group">
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
                            ])
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <label for="avatar" class="fz16 avatar-label">
                                <?= Yii::t('common', 'Avatar') ?>
                            </label>
                            <div class="inp-wrap d-flex flex-row avatar-img">
                                <img src="<?= Yii::getAlias("@storageUrl") ?>/avatars/<?= $profile->getAvatarPath() ?>" alt="avatar">
                            </div>
                            <div class="change-avatar-block hidden">
                                <?= $form->field($profile, 'avatar_path')->widget(FileApi::className(), [
                                    'settings' => [
                                        'url' => ['/site/fileapi-upload'],
                                    ],
                                    'crop' => true,
                                    'cropResizeWidth' => 100,
                                    'cropResizeHeight' => 100,
                                ])
                                ?>
                            </div>
                            <div class="position-absolute change-btn">
                                <button class="btn btn-outline-info btn-block">
                                    <?= Yii::t('common', 'Change') ?>
                                </button>
                            </div>                   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($profile, 'firstname')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($profile, 'gender')->dropDownlist([
                                UserProfile::GENDER_MALE => Yii::t('backend', 'Male'),
                                UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
                            ],
                            [
                                'prompt' => ''
                            ])
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 flex-column">
                            <?= $form->field($profile, 'lastname')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'roles')->checkboxList($roles) ?>
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($profile, 'other')->textarea(['rows' => 6, 'maxlength' => true]) ?>
                        </div>      
                    </div>
                    <?= $form->field($model, 'status')->hiddenInput(['value' => User::STATUS_ACTIVE])->label(false); ?>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-outline-info btn-block mt-2" data-dismiss="modal">
                    <?= Yii::t('backend', 'Cancel') ?>
                </button>
                <button type="submit" class="btn btn-success btn-block">
                    <?= Yii::t('backend', 'Save') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?php
    ActiveForm::end()
?>

<!-- script edit employee -->
<?= Yii::$app->view->render(
        '/net-manager/employees-new/script-edit-employee',
        [
            'id' => $id,
            'redrawModalSelector' => $redrawModalSelector,
            'deleteModalSelector' => $deleteModalSelector
        ]
    )
?>