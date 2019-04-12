<?php

use common\models\LoginForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model common\models\LoginForm */

$this->title = Yii::t('frontend', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-sign-in-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <div><?= \frontend\services\custom\Debugger::d($result) ?></div>
</div>
