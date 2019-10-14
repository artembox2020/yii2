<?php

use frontend\components\responsive\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $ass frontend\storages\AddressStatStorage */

?>
<div class="jlog-index">
    <div class="table-responsives <?= Yii::$app->headerBuilder->getJournalResponsiveClass() ?>">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => false,
                'summary' => '',
                'options' => [
                    'class' => 'address-average-loading',
                ],
                'rowOptions'=>function($model) use ($ass) {

                    return ['class' => $ass->getRowClassByItem($model)];
                },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'address',
                        'format' => 'raw',
                        'label' => Yii::t('graph', 'Address'),
                        'value' => function($model) use ($ass) {

                            return $ass->getAddressAverageLoadingFieldByItem($model, 'address');
                        }
                    ],
                    [
                        'attribute' => 'value',
                        'format' => 'raw',
                        'label' => Yii::t('graph', 'Loading'),
                        'value' => function($model) use ($ass) {

                            return $ass->getAddressAverageLoadingFieldByItem($model, 'value');
                        }
                    ]
                ]
            ])
        ?>
    </div>
</div>