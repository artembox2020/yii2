<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Company */

$this->title = Yii::t('backend', 'Update Company: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
