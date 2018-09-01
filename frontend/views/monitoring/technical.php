<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\ImeiDataSearch;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel  frontend\models\ImeiDataSearch*/
/* @var $monitoringController frontend\controllers\MonitoringController */
?>
<h1><?= Yii::t('frontend', 'Monitoring') ?></h1>
<div class="monitoring">
    <div class="form-group monitoring-shapter">
        <label for="type_packet"><?= Yii::t('frontend', 'Monitoring Shapter') ?></label>
        <?= Html::dropDownList(
                'monitoring_shapter', 
                'all',
                $monitoringShapters,
                [
                    'class' => 'form-control'
                ]
            );
        ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'summary' => '',
        'columns' => [
            [
                'label' => $monitoringShapters['common'],
                'format' => 'raw',
                'value' => function($model) use($monitoringController)
                {
                    return $monitoringController->renderCommonDataByImeiId($model->id);
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'common all']
            ],
            [
                'label' => $monitoringShapters['remote-connection'],
                'format' => 'raw',
                'value' => function($model) use($monitoringController)
                {
                    return $monitoringController->renderImeiCard($model->id);
                },
                'contentOptions' => ['class' => 'remote-connection all'],
                'headerOptions' => ['class' => 'remote-connection all']
            ],
            [
                'label' => $monitoringShapters['devices'],
                'format' => 'raw',
                'value' => function($model) use($monitoringController)
                {
                    return $monitoringController->renderDevicesByImeiId($model->id);
                },
                'contentOptions' => ['class' => 'devices all'],
                'headerOptions' => ['class' => 'devices all']
            ],
            [
                'label' => $monitoringShapters['terminal'],
                'format' => 'raw',
                'value' => function($model) use($monitoringController)
                {
                    return $monitoringController->renderTerminalDataByImeiId($model->id);
                },
                'contentOptions' => ['class' => 'terminal all'],
                'headerOptions' => ['class' => 'terminal all']
            ],
        ],
    ]); ?>
</div>
<?php echo $script; ?>