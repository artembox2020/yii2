<?php
/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Logger';
$this->params['breadcrumbs'][] = $this->title;
$dateFormat = "d.m.Y H:i";
$menu = [];
?>
<b>
    <?= $this->render('../_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>

<div class="border-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_at',
                'label' => Yii::t('logger', 'Date'),
                'value' => function($model) use($dateFormat)
                {
                    return date($dateFormat, $model['created_at']);
                }
            ],
            [
                'attribute' => 'type',
                'label' => Yii::t('logger', 'Type object'),
                'value' => 'type',
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('logger', 'Name object'),
                'value' => 'name',
            ],
            [
                'attribute' => 'type',
                'label' => Yii::t('logger', 'Serial number / Id / Inventory number'),
                'value' => 'number',
            ],
            [
                'attribute' => 'event',
                'label' => Yii::t('logger', 'Event'),
                'value' => 'event',
            ],
            [
                'attribute' => 'new_state',
                'label' => Yii::t('logger', 'New state'),
                'value' => 'new_state',
            ],
            [
                'attribute' => 'old_state',
                'label' => Yii::t('logger', 'Old state'),
                'value' => 'old_state',
            ],
            [
                'attribute' => 'address',
                'label' => Yii::t('logger', 'Address'),
                'value' => 'address',
            ],
            [
                'attribute' => 'who_is',
                'label' => Yii::t('logger', 'User'),
                'value' => 'who_is',
            ],
        ]
//        'itemView' => '_list',
//        'viewParams' => [
//            'fullView' => true,
//            'context' => 'main-page',
//            ],
    ]);
    ?>

</div>​
