<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use common\models\User;
use bs\Flatpickr\FlatpickrWidget;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $profile common\models\UserProfile */

?>
<div class="modal fade"
    id="createuser"
    tabindex="-1"
    role="dialog"
    aria-labelledby="create-user"
    aria-hidden="true"
>
    <?php 
        $form = ActiveForm::begin([
            'id' => 'user-create-form',
            'action' => '/account/default/create',
            'method' => 'post'
        ])
    ?>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header flex-column">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= Yii::t('common', 'Close') ?>">
                    <svg height="32" class="octicon octicon-x" viewBox="0 0 12 16" version="1.1" width="24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48L7.48 8z"></path>
                    </svg>
                </button>
                <h3 class="modal-title fw600" id="cr-user"><?= Yii::t('backend', 'Create user') ?></h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group flex-column">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => $model->username]) ?>
                        </div>
                        <div class="col-12 col-md-6 flex-column">
                            <?= $form->field($model, 'roles')->checkboxList($roles) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($profile, 'position')->textInput(['maxlength' => $profile->position]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-lg-6 form-group pos-rel">
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
                            <?= $form->field($profile, 'firstname')->textInput(['maxlength' => $profile->firstname]) ?>
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <?= $form->field($profile, 'lastname')->textInput(['maxlength' => $profile->lastname]) ?>
                        </div>     
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <?= Html::resetButton(
                    Yii::t('backend', 'Cancel'), 
                    [
                        'class' => 'btn btn-outline-info btn-block mt-2',
                        'data' => [
                            'dismiss' => 'modal'
                        ]
                    ]
                )
                ?>
                <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success btn-block']) ?>
            </div>
            <?= $form->field($model, 'status')->hiddenInput(['value' => User::STATUS_ACTIVE])->label(false); ?>
        </div>
    </div>
    <?php
        ActiveForm::end()
    ?>
</div>