<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\GdMashine */

$this->title = Yii::t('frontend', 'Create Gd Mashine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Gd Mashines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gd-mashine-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
