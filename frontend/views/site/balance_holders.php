<?php

    use frontend\components\responsive\GridView;

?>

<?=
    GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $balanceHolders,
        ]),
        'gridClass' => GridView::OPTIONS_DEFAULT_GRID_CLASS.' grid-bh',
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {

                    return Yii::$app->commonHelper->link($model).'<br>'.$model->address.'<br>'.$model->phone;
                }
            ],
            [
                'attribute' => 'address',
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'address'
                ],
                'contentOptions' => [
                    'class' => 'address'
                ],
                'value' => function($model) use ($balanceHoldersData) {

                    return \yii\grid\GridView::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'allModels' => $balanceHoldersData[$model->id],
                        ]),
                        'summary' => '',
                        'columns' => [
                            [
                                'attribute' => 'address.address',
                                'label' => Yii::t('frontend', 'Address'),
                                'format' => 'raw',
                                'value' => function($model) {

                                    return Yii::$app->commonHelper->link($model->address);
                                }
                            ],
                            [
                                'attribute' => 'address.imei',
                                'label' => Yii::t('frontend', 'Imei'),
                                'format' => 'raw',
                                'value' => function($model) {

                                    return $model->address->getImeiData();
                                }
                            ],
                            [
                                'attribute' => 'address.imei',
                                'label' => Yii::t('frontend', 'Device statuses'),
                                'format' => 'raw',
                                'value' => function($model) {

                                    return $model->address->getMashinesData($model->mashines);
                                }
                            ],
                        ]
                    ]);
                }
            ]
        ]
    ])
?>