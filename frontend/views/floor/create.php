<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Floor */

$this->title = Yii::t('frontend', 'Create Floor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Floors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="floor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
