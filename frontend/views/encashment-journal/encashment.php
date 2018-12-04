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
                'value' => 'date',
            ],
            [
                'label' => Yii::t('frontend', 'Time'),
                'format' => ['date', 'php:H:i'],
                'value' => 'date',
            ],
            [
                'label' => Yii::t('frontend', 'Address'),
                'format' => 'raw',
                'value' => function($data) use ($searchModel) {
                            $address = new \frontend\models\CbLog();
                    return $address->getAddress($data['address_id'])->address;
                },
            ],
            [
                    'label' => Yii::t('frontend', 'Encashment, hrn.'),
                'format' => 'raw',
                'value' => 'collection_counter'
            ],
        [
                'label' => Yii::t('frontend', 'Number of days from previous encashment'),
            'format' => 'raw',
            'value' => function($data) use ($searchModel) {
                $address = new \frontend\models\CbLog();
                return $address->getSumDaysPreviousAnAddress($data['unix_time_offset'], $data['address_id']);
            },
        ],
        [
                'label' => Yii::t('frontend', 'Non-combustible counter'),
            'format' => 'raw',
            'value' =>'fireproof_counter_hrn'
        ],
        [
                'label' => Yii::t('frontend', 'Previous non-burning counter'),
            'format' => 'raw',
            'value' =>''
        ],
        [
                'label' => Yii::t('frontend', 'Amount recounted, UAH'),
            'format' => 'raw',
            'value' =>''
        ],
        [
                'label' => Yii::t('frontend', 'Difference'),
            'format' => 'raw',
            'value' =>''
        ],
        ],
]); ?>
<?php Pjax::end(); ?>
</div>
