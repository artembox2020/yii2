<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\ImeiDataSearch;
use \yii\jui\AutoComplete;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use frontend\services\globals\EntityHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\ImeiDataSearch */

?>
<h1><?= Yii::t('frontend', 'Encashment Journal') ?></h1>

<div class="monitoring">
        <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => Yii::t('frontend', 'Date'),
                'format' =>'raw',
                'value' => 'created_at'
            ],
                [
                'attribute' => 'date_sum_last_encashment',
                'format' =>'raw',
                'header' => EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/calendar.png',
                        '/static/img/monitoring/date_last_encashment.png',
                    ],
                    $searchModel->attributeLabels()['date_sum_last_encashment']
                ),
                'value' => function($model) use ($searchModel) {
                    return (
                        $searchModel->getScalarDateAndSumLastEncashmentByImeiId($model->id) .
                        EntityHelper::makePopupWindow(
                            [],
                            $searchModel->attributeLabels()['date_sum_last_encashment'],
                            'top: -5px',
                            'height: 5px'
                        )
                    );
                }
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
    <br><br>
</div>
