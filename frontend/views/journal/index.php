<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\Imei;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
$this->title = Yii::t('frontend', 'Events Journal');
?>

<div class="jlog-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'filter' => false
                ],
                [
                    'attribute' => 'packet',
                    'filter' => false
                ],
                [
                    'attribute' => 'date',
                    'filter' => false
                ],
                [
                    'attribute' => 'imei',
                    'filter' => false
                ],
                [
                    'attribute' => 'address',
                    'filter' => false
                ],
                [
                    'attribute' => 'events',
                    'filter' => false
                ],
            ],
        ]); ?>
    </div>    
</div>
