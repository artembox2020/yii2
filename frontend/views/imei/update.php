<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Imei */

$this->title = Yii::t('frontend', 'Update Imei: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Imeis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="imei-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address
    ]) ?>

</div>
