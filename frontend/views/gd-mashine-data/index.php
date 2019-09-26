<?php

use yii\helpers\Html;
use frontend\components\responsive\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\GdMashineDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Gd Mashine Datas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gd-mashine-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create Gd Mashine Data'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'imei_id',
            'type_mashine',
            'serial_number',
            'gel_in_tank',
            //'bill_cash',
            //'status',
            //'created_at',
            //'updated_at',
            //'is_deleted',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
