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
                'attribute' => 'date',
                'format' => 'raw',
                'filter' => $this->render('/journal/filters/main', ['name'=> 'date', 'params' => $params, 'searchModel' => $searchModel]),
                'value' => function($model)
                {
                    $dateParts = explode(' ', $model->date);

                    return date('m/d/y', strtotime($dateParts[0])).' '.$dateParts[1];
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'address',
                'filter' =>  $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
                'value' => function($model)
                {
                    $addressParts = explode(",", $model->address);
                    $countParts = count($addressParts);

                    return $addressParts[$countParts-2]." (".$addressParts[$countParts-1].")";
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'on_modem_account_number',
                'format' => 'raw',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    if (is_null($packetData['on_modem_account'])) {
                        
                        return null;
                    }

                    return $packetData['on_modem_account'].' - '.$packetData['phone_module_number'];
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'level_signal',
                'format' => 'raw',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    return $packetData['level_signal'];
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
                'attribute' => 'number_channel',
                'value' => function($model)
                {
                    $packet = $model->packet;
                    $cParser = new CParser();
                    $packetData = $cParser->iParse($packet);

                    return $packetData['number_channel'];
                }
            ],
        ],
    ]);
    ?>
</div>