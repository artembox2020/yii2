<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use frontend\models\Imei;
use frontend\models\Jlog;
use frontend\services\globals\EntityHelper;
use yii\widgets\Pjax;
use \yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
$this->title = Yii::t('frontend', 'Events Journal');
?>

<div class="jlog-index">
    <?php

        // renders filter block

        if ($params['type_packet'] == Jlog::TYPE_PACKET_ENCASHMENT) {
            $this->title = Yii::t('logs', 'Journal Encashment');

            echo Yii::$app->view->render(
                '/journal/filter-encashment-block',
                [
                    'params' => $params,
                    'addresses' => $addresses,
                    'submitFormOnInputEvents' => $submitFormOnInputEvents,
                    'typePackets' => $typePackets,
                    'searchModel' => $searchModel,
                    'pageSizes' => $pageSizes
                ]
            );
        } else {

            echo Yii::$app->view->render(
                '/journal/filter-block',
                [
                    'params' => $params,
                    'addresses' => $addresses,
                    'submitFormOnInputEvents' => $submitFormOnInputEvents,
                    'typePackets' => $typePackets,
                    'pageSizes' => $pageSizes
                ]
            );
        }
    ?>

    <?php
        // renders appropriate view by data packet

        echo $journalController->renderAppropriatePacket($params, $dataProvider);
    ?>

    <?php
        echo $removeRedundantGrids;
        echo $columnFilterScript;
        Pjax::end();
    ?>
</div>
