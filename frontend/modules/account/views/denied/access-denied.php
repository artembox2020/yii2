<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */

//$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Companies'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->session->getFlash('AccessDenied'); ?>
    </p>
</div>
