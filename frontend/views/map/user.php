<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\CustomerCards;
use \frontend\models\AddressImeiData;
use \common\models\UserProfile;

/* @var $action int */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $cards backend\models\search\CardSearch */

?>
<div class="net-manager-new">
    <div class="jlog-index map-user">
        <?= 
            Yii::$app->view->render('/map/templates/shapter', ['action' => $action]);
        ?>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $cards,
                'columns' => [
                    [
                        'label' => yii::t('map', 'User'),
                        'format' => 'raw',
                        'value' => function ($model) {
                            $userProfile = new UserProfile();

                            return \yii\helpers\Html::a(
                                $userProfile->findFlnameByUserId($model->user_id),
                                '/map/userscard?userId='.$model->user_id
                            );
                        }
                    ],
                    [
                        'label' => yii::t('map', 'Circulation'),
                        'value' => function($model) use ($transaction) {

                            return $transaction->findCirculationByUserId($model->user_id);
                        }
                    ],
                    [
                        'label' => yii::t('map', 'Address'),
                        'value' => function($model) use ($cards) {

                            return $cards->findAddressByCardNo($cards->findCardsByUserId($model->user_id));
                        }
                    ],
                    [
                        'label' => yii::t('map', 'Last action'),
                        'value' => function($model) use ($cards) {

                            return $cards->findLastActivityByCardNo($cards->findCardsByUserId($model->user_id));
                        }
                    ],
                ],
            ])
            ?>
        </div>
    </div>
</div>