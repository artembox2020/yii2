<?php

use yii\grid\GridView;

/* @var $searchModel frontend\models\CbLogSearch */
/* @var $dataProvider yii\data\ArrayDataProvider */

?>
<span class="glyphicon glyphicon-plus banknote_nominals"></span>

<div class="banknote-nominals-container">
    <div class="banknote-nominals hide">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => false,
            'options' => [
                'class' => 'banknote-nominals-grid'
            ],
            'columns' => [
                [
                    'attribute' => 'address',
                    'format' => 'raw',
                    'label' => false,
                    'value'=> function ($model) use ($searchModel) {

                        return $searchModel->getAddressGridView($model);
                    },
                ],
                [
                    'attribute' => 'nominals',
                    'format' => 'raw',
                    'label' => false,
                    'value'=> function ($model) use ($searchModel) {

                        return $searchModel->getNominalsView($model).$searchModel->getCoinNominalsView($model);
                    },
                ],
                [
                    'attribute' => 'total',
                    'format' => 'raw',
                    'label' => false,
                    'value' => function ($model) use ($searchModel) {

                        return $searchModel->getNominalsTotalView($model).$searchModel->getCoinNominalsTotalView($model);
                    }
                ]
            ]
        ])
        ?>
    </div>
</div>