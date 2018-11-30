<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \frontend\models\Jlog;
use frontend\models\Imei;
use frontend\services\parser\CParser;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */
?>

<div class="table-responsives">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'journal-grid-view',
        ],
        'columns' => [
            [
                'attribute' => 'address',
                'filter' =>  $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
                'value' => function($model)
                {
                    
                    return $model->address;
                }
            ],
            [
                'attribute' => 'date',
                'format' => 'raw',
                'filter' => $this->render('/journal/filters/main', ['name'=> 'date', 'params' => $params, 'searchModel' => $searchModel]),
                'value' => function($model)
                {
                    $dateParts = explode(' ', $model->date);

                    return $dateParts[1].'<br>'.$dateParts[0];
                }
            ],
            [
                'attribute' => 'imei',
                'format' => 'raw',
                'filter' => $this->render('/journal/filters/main', ['name'=> 'imei', 'params' => $params]),
                'value' => function($model)
                {
                    $imei = Imei::find()->where(['id' => $model['imei_id']])->one();

                    return $imei->imei;
                }
            ],
            [
                'attribute' => 'level_signal',
                'format' => 'raw',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->dParse($packet);

                    return $packetData['level_signal'];
                }
            ],
            [
                'attribute' => 'money_in_banknotes',
                'format' => 'raw',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->dParse($packet);

                    return $packetData['money_in_banknotes'];
                }
            ],
        ],
    ]);
    ?>
</div>