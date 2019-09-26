<?php

use yii\helpers\Html;
use frontend\components\responsive\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashineData */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Wm Mashine Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wm-mashine-data-view">

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
            'wm_mashine_id',
            'type_mashine',
            'number_device',
            'level_signal',
            'bill_cash',
            'door_position',
            'door_block_led',
            'status',
            'created_at',
            'updated_at',
            'is_deleted',
            'deleted_at',
        ],
    ]) ?>

</div>
