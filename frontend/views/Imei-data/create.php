<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ImeiData */

$this->title = Yii::t('frontend', 'Create Imei Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Imei Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imei-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
