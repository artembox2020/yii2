<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\GdMashineData */

$this->title = Yii::t('frontend', 'Update Gd Mashine Data: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Gd Mashine Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="gd-mashine-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
