<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashine */

$this->title = Yii::t('frontend', 'Create Wm Mashine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Wm Mashines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wm-mashine-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
