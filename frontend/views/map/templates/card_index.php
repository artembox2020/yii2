<?php

use yii\grid\GridView;
use \common\models\UserProfile;
use frontend\models\CustomerCards;

/* @var $cards backend\models\search\CardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $cards,
        'columns' => [
            [
                'label' => yii::t('backend', 'Card no'),
                'attribute' => 'card_no',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model) {

                    return \yii\helpers\Html::a($model->card_no, '/map/cardofcard?cardNo='.$model->card_no);
                }
            ],
            [
                'label' => yii::t('backend', 'Balance'),
                'attribute' => 'balance',
                'filter' => false,
            ],
            [
                'label' => yii::t('backend', 'Discount'),
                'attribute' => 'discount',
                'filter' => false,
            ],
            [
                'label' => yii::t('map', 'User'),
                'contentOptions' => ['class' => 'user'],
                'headerOptions' => ['class' => 'user'],
                'format' => 'raw',
                'value' => function($model) {
                    $userProfile = new UserProfile();

                    return \yii\helpers\Html::a(
                        $userProfile->findFlnameByUserId($model->user_id),
                        '/map/userscard?userId='.$model->user_id
                    );
                },
                'filter' => false
            ],
            [
                'label' => yii::t('map', 'Address'),
                'value' => function($model) use ($cards) {

                    return $cards->findAddressByCardNo($model->card_no);
                },
                'filter' => false,
            ],
            [
                'label' => yii::t('map', 'Last action'),
                'value' => function($model) use ($cards) {

                    return $cards->findLastActivityByCardNo($model->card_no);
                },
                'filter' => false,
            ],
            [
                'label' => yii::t('backend', 'Status'),
                'attribute' => 'status',
                'value' => function($cards) {

                    return CustomerCards::statuses($cards->status);
                },
                'filter' => false
            ],
            [
                'label' => Yii::t('frontend', 'Actions'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'actions'],
                'contentOptions' => ['class' => 'actions'],
                'value' => function($model) {

                    return
                        '<div class="cardinfo cardinfo-index">'.
                            Yii::$app->view->render('/map/templates/card_actions', ['card' => $model]).
                        '</div>';
                }
            ]
        ],
    ])
    ?>
</div>

<?= Yii::$app->view->render(
        '@frontend/views/map/js/main',
        ['design' => Yii::$app->mapBuilder::CARD_ACTIONS_SIMPLE_DESIGN]
    )
?>

<?= Yii::$app->view->render('/map/css/card_actions') ?>