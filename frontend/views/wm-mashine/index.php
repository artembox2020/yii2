<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WmMashineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Wm Mashines');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wm-mashine-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create Wm Mashine'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'imei_id',
            'type_mashine',
            'number_device',
            'level_signal',
            //'bill_cash',
            //'door_position',
            //'door_block_led',
            //'status',
            //'created_at',
            //'updated_at',
            //'is_deleted',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
