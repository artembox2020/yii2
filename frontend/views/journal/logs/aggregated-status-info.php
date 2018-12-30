<?php

use yii\widgets\DetailView;

/* @var $model array */
/* @var $wmStatus string */
/* @var $washingMode string */
/* @var $washTemperature string */
/* @var $spinType string */
/* @var $additionalWashOptions string */
/* @var $cpStatus string */
?>

<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('logs', 'WM Status'),
                'value' => $wmStatus
            ],
            [
                'label' =>  Yii::t('logs', 'Washing Mode'),
                'format' => 'raw',
                'value' => $washingMode
            ],
            [
                'label' => Yii::t('logs', 'Wash Temperature'),
                'value' => $washTemperature
            ],
            [
                'label' =>  Yii::t('logs', 'Spin Type'),
                'format' => 'raw',
                'value' => $spinType
            ],
            [
                'label' => Yii::t('logs', 'Additional Wash Options'),
                'value' => $additionalWashOptions
            ],
            [
                'label' =>  Yii::t('logs', 'Cp Status'),
                'format' => 'raw',
                'value' => $cpStatus
            ],
        ]
    ]);
?>