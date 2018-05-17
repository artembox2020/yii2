<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $addressBalanceHolders */
/* @var $users */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* var address array */
$address = array();

/* var imei array */
$imei = array();

/* var machine array */
$machine = array();

/* var machine array */
$gd_machine = array();

?>
<h1>net-manager/index</h1>
<?php $menu = []; ?>
<b>
<?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>

<div class="account-default-users">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create user'), ['/net-manager/create-employee'], ['class' => 'btn btn-success']) ?>
        <!-- <?= Html::a(Yii::t('frontend', 'Roles'), ['/rbac/access/role'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('frontend', 'Permissions'), ['/rbac/access/permission'], ['class' => 'btn btn-success']) ?> -->
    </p>

    <?php foreach ($users as $user) : ?>
        <?= $user->username . ' ' .
        Yii::t('backend', 'Position') . ': ' .
        $user->position . ' ' . 
        Yii::t('frontend','Role') . ': ' .
        Yii::t('frontend', $user->getUserRoleName($user->id)); ?>
    <div style="display: inline-flex">
        <?php $form = ActiveForm::begin([
            'action' => '/net-manager/view-employee'
        ]) ?>
        <?=  Html::hiddenInput('id', $user->id); ?>
        <?= Html::submitInput(Yii::t('frontend', 'view'), ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>
        <?php $form = ActiveForm::begin([
            'action' => '/net-manager/edit-employee'
        ]) ?>
        <?=  Html::hiddenInput('id', $user->id); ?>
        <?= Html::submitInput(Yii::t('frontend', 'edit'), ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div> <br><br>
<!--        <a href="/net-manager/view-employee">view</a> |-->
    <?php endforeach; ?>

    
</div>
