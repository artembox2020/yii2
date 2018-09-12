<?php
error_reporting( E_ERROR );

use yii\jui\DatePicker;
use yii\helpers\Html;
use frontend\models\Devices;
use frontend\models\Zlog;
use yii\grid\GridView;

echo '- - -summary journal - - -';
?>
<h1><?= Yii::t('frontend', 'Summary Journal') ?></h1>
<div class="summary-journal">
    <!--<div class="form-group monitoring-shapter">
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
    </div>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'summary' => '',
        'columns' => [
            [
                'label' => Yii::t('frontend', 'ID'),
                'format' => 'raw',
                'value' => function($model, $key, $index)
                {
                    return ++$index;
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'common all']
            ],
        ],
    ]); ?>
</div>
<?php echo $script; ?>
