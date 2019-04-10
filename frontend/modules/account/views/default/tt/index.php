<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\User */

//$this->title = Yii::t('frontend', 'Update Address Balance Holder: {nameAttribute}', [
//    'nameAttribute' => $model->name,
//]);
?>
<br>
<div class="address-balance-holder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
