<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \frontend\models\Jlog;
use frontend\models\JlogDataCpSearch;
use frontend\models\Imei;
use frontend\services\parser\CParser;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogDataCpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

?>

<div class="table-responsives <?= Yii::$app->headerBuilder->getJournalResponsiveClass() ?>">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'journal-grid-view',
        ],
        'columns' => [
            [
                'attribute' => 'date',
                'format' => 'raw',
                'label' => Yii::t('imeiData', 'Date Start'),
                'filter' => $this->render(
                    '/journal/filters/main',
                    [
                        'name'=> 'date',
                        'params' => $params,
                        'searchModel' => $searchModel,
                        'sortType' => $searchFilter->getSortType($params, 'date')
                    ]
                ),
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getDateStartView($model);   
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'date_end',
                'format' => 'raw',
                'label' => Yii::t('imeiData', 'Date End'),
                'filter' => false,
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getDateEndView($model);
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'address',
                'format' => 'raw',
                'filter' =>  $this->render(
                    '/journal/filters/main',
                    [
                        'name'=> 'address',
                        'params' => $params,
                        'searchModel' => $searchModel,
                        'sortType' => $searchFilter->getSortType($params, 'address')
                    ]
                ),
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getAddressView($model);
                },
                'contentOptions' => ['class' => 'inline']
            ],
            [
                'attribute' => 'cp_status',
                'format' => 'raw',
                'label' => Yii::t('imeiData', 'CP Status'),
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getCPStatusFromDataPacket($model->packet);
                }
            ],
            [
                'attribute' => 'evt_bill_acceptance',
                'format' => 'raw',
                'label' => Yii::t('imeiData', 'Event Bill Acceptance'),
                'value' => function($model) use ($searchModel)
                {

                    return  $searchModel->getEvtBillValidatorFromDataPacket($model->packet);
                }
            ],
            [
                'attribute' => 'in_banknotes',
                'format' => 'raw',
                'label' => Yii::t('frontend', 'In Banknotes'),
                'value' => function($model) use ($searchModel)
                {
                    return $searchModel->getParamFromDataPacket($model->packet, 'in_banknotes');
                }
            ],
            [
                'attribute' => 'money_in_banknotes',
                'format' => 'raw',
                'label' => Yii::t('frontend', 'Money In Banknotes'),
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getParamFromDataPacket($model->packet, 'money_in_banknotes');
                }
            ],
            [
                'attribute' => 'fireproof_residue',
                'format' => 'raw',
                'label' => Yii::t('frontend', 'Fireproof Residue'),
                'value' => function($model) use ($searchModel)
                {

                    return $searchModel->getParamFromDataPacket($model->packet, 'fireproof_residue');
                }
            ],
        ],
    ]);
    ?>
</div>