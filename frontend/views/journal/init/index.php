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
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getDateView($model);                 
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'address',
                'filter' =>  $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
                'value' => function($model) use ($searchModel)
                {
                    
                    return $searchModel->getAddressView($model);   
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'on_modem_account_number',
                'format' => 'raw',
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getOnModemAccountNumber($model);
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'level_signal',
                'format' => 'raw',
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getLevelSignal($model);
                }
            ],
            [
                'attribute' => 'pcb_version',
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getPcbVersion($model);
                }
            ],
            [
                'attribute' => 'firmware_version_cpu',
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getFirmwareVersionCpu($model);
                }
            ],
            [
                'attribute' => 'firmware_6lowpan',
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getFirmware6Lowpan($model);
                }
            ],
            [
                'attribute' => 'number_channel',
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getNumberChannel($model);
                }
            ],
        ],
    ]);
    ?>
</div>