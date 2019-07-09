<?php

use yii\helpers\Html;
use frontend\models\ImeiDataSearch;
use \yii\jui\AutoComplete;
use yii\widgets\Pjax;
use frontend\components\MonitoringBuilder;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel  frontend\models\ImeiDataSearch*/
/* @var $monitoringController frontend\controllers\MonitoringController */
/* @var $monitoringBuilder frontend\components\MonitoringBuilder */
/* @var $addresses array */
/* @var $postParams array */

?>
<div class="monitoring-new">
    <?php
        echo Yii::$app->view->render('data/filter_form_new', [
            'params' => $params,
            'addresses' => $addresses,
            'sortOrders' => $sortOrders
        ]);
    ?>

    <?php
        Pjax::begin(['id' => 'monitoring-pjax-grid-container']);
    ?>

    <div class="table-responsives monitoring-grid-view">
        <?= $monitoringBuilder->renderFinancial($dataProvider, $searchModel, $postParams) ?>

        <?= Yii::$app->view->render('data/pjax_form', ['params' => $params]); ?>
    </div>
</div>

<?php
    echo $script;
    Pjax::end();
?>