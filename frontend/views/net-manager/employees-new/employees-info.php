<?php

use common\models\User;
use yii\helpers\Html;
use backend\models\UserForm;
use common\models\UserProfile;
use frontend\controllers\NetManagerController;
use frontend\services\logger\src\service\LoggerService;
use frontend\services\logger\src\DummyLoggerDto;
use frontend\services\logger\src\storage\DummyStorage;
use frontend\services\globals\Entity;
use yii\bootstrap\ActiveForm;

/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $pageSize int */
/* @var $model \yii\common\User */

?>
<section class="coworkers__create-user container-fluid mt-5">
    <div class="create-user__head d-flex justify-content-between">
        <span class="createuser-btn">
            <button data-toggle="modal" data-target="#createuser">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </button> <?= Yii::t('backend', 'Create user') ?>
        </span>

        <span class="breadcrbs pr-5 hidden"><?= Yii::t('frontend', 'Employees') ?></span>
    </div>

    <div class="createuser__search d-flex">
        <span class="search-icon"><i class="fa fa-search"></i></span>
        <input
            type="text"
            class="form-control"
            placeholder="<?= Yii::t('frontend', 'NameSurnamePatronymicPosition') ?>" id="createuser-search"
        >
        <span class="records-shown">
            <?= Yii::t('frontend', 'Shown') ?>&nbsp;
            <span class="number-showed">
                <?= ($count = $dataProvider->query->count()) < $pageSize ? $count : $pageSize ?>
            </span>&nbsp;<?= Yii::t('frontend', 'From') ?>&nbsp;
            <span class="number-all">
                <?= $count ?>
            </span>&nbsp;<?= Yii::t('frontend', 'Itemis') ?>
        </span>
    </div>

    <div class="user-info">
        <table class="table table-striped b1 table-sm table-responsive-md fz16">
            <tr>
                <th><?= Yii::t('frontend', 'Number') ?></th>
                <th><?= Yii::t('frontend', 'NameLastname') ?></th>
                <th><?= Yii::t('frontend', 'Position') ?></th>
                <th><?= Yii::t('common', 'Server Rights') ?></th>
                <th><?= Yii::t('common', 'Actions') ?></th>
            </tr>
        <?php $counter = 0; foreach ($dataProvider->query->all() as $item): ?>
            <tr class="tr <?= ++$counter > $pageSize ? 'hidden' : '' ?>">
                <td>
                    <?= $item->id ?>
                </td>
                <td class="name">
                    <?= $item->userProfile->firstname.' '.$item->userProfile->lastname ?>
                </td>
                <td class="position">
                    <?= $item->userProfile->position ?>
                </td>
                <td>
                    <?= $item->getUserRoleName($item->id) ?>
                </td>
                <td class="actions" style="white-space: nowrap;">
                    <button class="eye">
                        <?= Html::a('<i class="fas fa-eye"></i>', ['/net-manager/view-employee', 'id' =>$item->id]) ?>
                    </button>
                    <button>
                        <span
                            class = "edit-pen"
                            data-toggle = "modal"
                            data-target = "#editcoworker"
                            data-id="<?= $item->id ?>"
                        >
                            <?= '<img src="'.Yii::getAlias("@storageUrl/main-new").'/img/edit-pen.svg" alt="'.Yii::t('frontend', 'Edit') .'">' ?>
                        </span>
                    </button>
                    <button>
                        <span data-toggle = "modal" data-target = "#del-coworker" data-delete-id = "<?= $item->id ?>">
                            <img src="<?= Yii::getAlias("@storageUrl/main-new") ?>/img/delete.svg" alt="<?= Yii::t('frontend', 'Edit') ?>">
                        </span>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    <?php if ($count > $pageSize): ?>
        <span class="text-left text-md-center mb-5 showmore">
            <button type="button" class="btn btn-info">
                <?= Yii::t('logger', 'Show more') ?>
            </button>
        </span>
        <span class="text-left text-md-center mb-5 showless hidden">
            <button type="button" class="btn btn-info">
                <?= Yii::t('logger', 'Show less') ?>
            </button>
        </span>
    <?php endif; ?>
    </div>

    <div class="summary-data">
        <h3>
            <?= Yii::t('frontend','Summary Technical Data') ?>
        </h3>
        <p>
            <?= Yii::t('frontend', 'Count Employee') ?>: <?= $model->getUserCount() ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'Count Administrative Employee') ?>: <?= $model->getUserCountByRoles([User::ROLE_ADMINISTRATOR]) ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'Count Financier Employee') ?>: <?= $model->getUserCountByRoles([User::ROLE_FINANCIER]) ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'Count Technical Employee') ?>: <?= $model->getUserCountByRoles([User::ROLE_TECHNICIAN]) ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'Count Other Employee') ?>: <?= $model->getUserOtherCount() ?>
        </p>
    </div>

    <input type="hidden" class="page-size" value="<?= $pageSize < $count ? $pageSize : $count ?>" />
    <input type="hidden" class="page-size-initial" value="<?= $pageSize ?>" />
</section>

<?=
    Yii::$app->runAction(
        '/net-manager/create-employee',
        []
    )
?>

<?=
    Yii::$app->runAction(
        '/net-manager/edit-employee-new',
        ['dataProvider' => $dataProvider]
    )
?>

<?= ''/*Yii::$app->view->render('/net-manager/employees-new/delete-employee')*/ ?>