<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\GdMashineData */

$this->title = Yii::t('frontend', 'Create Gd Mashine Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Gd Mashine Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gd-mashine-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
