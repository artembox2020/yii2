<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\components\responsive\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Imei */
/* @var $model frontend\models\BalanceHolder */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $address */
?>
<b><?= Yii::t('frontend', 'Employees company') ?></b> <?= Html::a(Yii::t('frontend', 'Add Employee'), ['/account/default/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    <p>
        <?php foreach ($users as $user) : ?>
            <?= $user->username . ' ' .
            Yii::t('frontend','Role') . ': ' .
            Yii::t('frontend', $user->getUserRoleName($user->id)); ?> <br>
        <?php endforeach; ?>
    </p>