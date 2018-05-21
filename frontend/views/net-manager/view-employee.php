<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="account-default-users">
    <?= $model->username . ' 
    ' . Yii::t('frontend', $model->getUserRoleName($model->id)); ?>
</div>
