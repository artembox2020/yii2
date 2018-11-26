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
//                'format' => ['date', 'php:H:i:s d.m.Y'],
                'format' => ['date', 'php:d.m.Y'],
                'value' => 'created_at',
            ],
            [
                'label' => Yii::t('frontend', 'Time'),
                'format' => ['date', 'php:H:i'],
                'value' => 'created_at',
            ],
            [
                'label' => Yii::t('frontend', 'Address'),
                'format' => 'raw',
                'value' => 'address.address',
            ],
            [
                'label' => Yii::t('frontend', 'Encashment, hrn.'),
                'format' => 'raw',
                'value' => function($model) use ($searchModel) {
                    return (
                    $searchModel->getScalarSumLastEncashmentByImeiId($model->id)
//                        EntityHelper::makePopupWindow(
//                            [],
//                            $searchModel->attributeLabels()['date_sum_last_encashment'],
//                            'top: -5px',
//                            'height: 5px')
                    );
                }
            ],
            [
                'label' => Yii::t('frontend', 'Number of days from previous encashment'),
                'format' => 'raw',
                'value' => function($model) use ($searchModel) {
                    return (
                    $searchModel->getScalarSumLastEncashmentByImeiId($model->id)
//                        EntityHelper::makePopupWindow(
//                            [],
//                            $searchModel->attributeLabels()['date_sum_last_encashment'],
//                            'top: -5px',
//                            'height: 5px')
                    );
                }
            ],
//
            ],
        ]); ?>
    <?php Pjax::end(); ?>
    <br><br>
</div>
