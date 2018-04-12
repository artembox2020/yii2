<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashineData */

$this->title = Yii::t('frontend', 'Create Wm Mashine Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Wm Mashine Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wm-mashine-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
