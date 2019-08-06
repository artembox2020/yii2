<?php

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $userForm common\models\UserForm */
/* @var $profile common\models\UserProfile */
/* @var $roles array */

?>
<div class="net-manager-new">
    <?= $this->render('_sub_menu-new') ?>
    <section class="coworker-card container-fluid px-5 py-4">
        <div class="media">
            <img
                class="mr-3"
                src="/storage/avatars/<?= $model->userProfile->getAvatarPath() ?>"
                alt="avatar"
            >
            <div class="media-body">
                <div class="d-flex justify-content-between">
                    <h5 class="mt-0 fw600 username"><?= $model->username ?></h5>
                    <input type="hidden" class="userid" value="<?= $model->id ?>" />
                    <div class="breds fz16">
                        <span class="d-inline-block text-right color-edit">
                            <a href="/net-manager/employees"><?= Yii::t('frontend', 'Employees') ?> &lt; </a>
                        </span>
                        <span class="d-inline-block text-right">
                            &nbsp;&nbsp;<?= Yii::t('common','Worker Card') ?>
                        </span>
                    </div>
                </div>
                <div class="d-block edits">
                    <button class="btn-transparent" data-toggle="modal" data-target="#editcoworker">
                        <img src="<?= Yii::getAlias("@storageUrl/main-new") ?>/img/edit-pen.svg" alt="<?= Yii::t('frontend', 'Edit') ?>">
                        <span class="color-edit fz12 pl-2"><?= Yii::t('frontend', 'Edit') ?></span>
                    </button>
                    <button
                        class="btn-transparent"
                        data-toggle = "modal"
                        data-target = "#del-coworker"
                        data-delete-id = "<?= $model->id ?>"
                    >
                        <img src="<?= Yii::getAlias("@storageUrl/main-new") ?>/img/delete.svg" alt="<?= Yii::t('frontend', 'Delete') ?>">
                        <span class="color-edit fz12 pl-2"><?= Yii::t('frontend', 'Delete') ?></span>
                    </button>
                </div>
            </div>            
        </div>
        <span class="d-block fz16 mt-3">
            <?= Yii::t('common','Position') ?>: <?= $model->userProfile->position ?>
        </span>
        <span class="d-block fz16">
            <?= Yii::t('common','Server Rights') ?>: <?= $model->getUserRoleName($model->id) ?>
        </span>
        <span class="d-block fz16 mb-5">
            <?= Yii::t('common','Birthday') ?>: <?= date("d.m.Y",$model->userProfile->birthday) ?>
        </span>
        <?= Yii::$app->view->render(
            '/net-manager/employees-new/edit-employee',
            [
                'model' => $userForm,
                'profile' => $profile,
                'roles' => $roles,
                'index' => '',
                'id' => $model->id,
                'redrawModalSelector' => "*[data-target='#editcoworker']",
                'deleteModalSelector' => ".edits button[data-target='#del-coworker']"
            ]
        )
        ?>
        <?=  Yii::$app->view->render('/net-manager/employees-new/delete-employee') ?>
    </section>
</div>