<?php

use yii\helpers\Html;
use frontend\components\responsive\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GdMashine */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Gd Mashines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gd-mashine-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'imei_id',
            'serial_number',
            'gel_in_tank',
            'status',
            'created_at:datetime',
            'updated_at:datetime',
//            'is_deleted',
//            'deleted_at',
        ],
    ]) ?>

</div>
