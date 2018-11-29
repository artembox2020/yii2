<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \frontend\models\Jlog;
use frontend\models\Imei;
use frontend\services\parser\CParser;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogInitSearch */
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

                    return $imei->imei.'<br>'.$imei->phone_module_number;
                }
            ],
            [
                'attribute' => 'pcb_version',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    return $packetData['pcb_version'];
                }
            ],
            [
                'attribute' => 'firmware_version_cpu',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    return $packetData['firmware_version_cpu'];
                }
            ],
            [
                'attribute' => 'firmware_6lowpan',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    return $packetData['firmware_6lowpan'];
                }
            ],
            [
                'attribute' => 'type_bill_acceptance',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    return $packetData['type_bill_acceptance'];
                }
            ],
            [
                'attribute' => 'serial_number_kp',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    return $packetData['serial_number_kp'];
                }
            ],
        ],
    ]);
    ?>
</div>