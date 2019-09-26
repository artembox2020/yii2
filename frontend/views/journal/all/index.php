<?php

use yii\helpers\Html;
use frontend\components\responsive\GridView;
use \frontend\models\Jlog;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

echo 'l='.Yii::$app->layout;
?>

<div class="table-responsives <?= Yii::$app->layout == 'main-new' ? 'table-responsive' : '' ?>">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'journal-grid-view',
        ],
        'columns' => [
            [
                'attribute' => 'id',
                'filter' =>  $this->render('/journal/filters/main', ['name'=> 'id', 'params' => $params]),
            ],
            [
                'attribute' => 'type_packet',
                'value' => function($model)
                {

                    return Jlog::getTypePacketName($model->type_packet);
                },
                'filter' => $this->render('/journal/filters/main', ['name'=> 'type_packet', 'params' => $params]),
            ],
            [
                'attribute' => 'date',
                'filter' => $this->render('/journal/filters/main', ['name'=> 'date', 'params' => $params, 'searchModel' => $searchModel]),
            ],
            [
                'attribute' => 'imei',
                'filter' => $this->render('/journal/filters/main', ['name'=> 'imei', 'params' => $params]),
            ],
            [
                'attribute' => 'address',
                'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
            ],
            [
                'attribute' => 'events',
                'filter' => false,
                'content' => function($model)
                {

                    return '';
                }
            ],
        ],
    ]);
    ?>
</div>