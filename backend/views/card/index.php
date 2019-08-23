<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\CustomerCards;

$this->title = Yii::t('backend', 'Cards');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Yii::$app->view->render('@frontend/views/map/templates/update-map-alert') ?>

<div class="jlog-index map-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $cards,
        'columns' => [
            [
                'label' => yii::t('backend', 'Card no'),
                'attribute' => 'card_no',
            ],
            [
                'label' => yii::t('backend', 'Balance'),
                'attribute' => 'balance',
            ],
            [
                'label' => yii::t('backend', 'Discount'),
                'attribute' => 'discount',
            ],
            [
                'label' => yii::t('backend', 'Status'),
                'attribute' => 'status',
                'value' => function($cards) {

                    return CustomerCards::statuses($cards->status);
                },
                'filter' => CustomerCards::statuses(),
            ],
            [
                'label' => Yii::t('frontend', 'Actions'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'actions'],
                'contentOptions' => ['class' => 'actions'],
                'value' => function($model) {

                    return
                        '<div class="cardinfo cardinfo-index">'.
                            Yii::$app->view->render(
                                '@frontend/views/map/templates/card_actions',
                                [
                                    'card' => $model,
                                ]
                            ).
                        '</div>';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ])
    ?>
</div>

<?= Yii::$app->view->render(
        '@frontend/views/map/js/main',
        ['design' => Yii::$app->mapBuilder::CARD_ACTIONS_EXTENDED_DESIGN]
    )
?>

<?= Yii::$app->view->render('@frontend/views/map/css/card_actions') ?>