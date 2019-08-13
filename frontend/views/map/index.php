<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\CustomerCards;
use \frontend\models\AddressImeiData;
use \common\models\UserProfile;

$this->title = Yii::t('backend', 'Cards');
$this->params['breadcrumbs'][] = $this->title;

$imei = \frontend\models\Imei::findOne(42);
$timestamp = time();
$addresImeiData = new AddressImeiData();
//echo $action;
//echo '<pre>';
//print_r($addresImeiData->findAddressIdByImeiAndTimestamp($imei, time()));
//echo '</pre>';

?>

<?= 
    Yii::$app->view->render('/map/templates/shapter', ['action' => $action]);
?>
<div class="jlog-index map-index">
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $cards,
            'columns' => [
                [
                    'label' => yii::t('backend', 'Card no'),
                    'attribute' => 'card_no',
                    'filter' => false,
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
                    'value' => function($model) {
                        $userProfile = new UserProfile();

                        return $userProfile->findFlnameByUserId($model->user_id);
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
                    'filter' => false//CustomerCards::statuses(),
                ]
            ],
        ])
        ?>
    </div>
</div>