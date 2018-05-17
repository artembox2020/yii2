<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="account-default-users">
    <?= $model->username . ' 
    ' . $model->position . ' 
    ' . Yii::t('frontend', $model->getUserRoleName($model->id)) . '
    ' . $model->birthday; ?>
</div>
