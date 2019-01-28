<?php

/* @var $dataProvider yii\data\ArrayDataProvider */
?>

<?=
    \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'total-grid'
        ],
        'columns' => [
            [
                'attribute' => 'total',
                'label' => Yii::t('logs', 'Total Sum')
            ]
        ]
    ]);
?>