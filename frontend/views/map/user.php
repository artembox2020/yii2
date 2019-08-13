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
//echo '<pre>';
//print_r($addresImeiData->findAddressIdByImeiAndTimestamp($imei, time()));
//echo '</pre>';

?>

<?= 
    Yii::$app->view->render('/map/templates/shapter', ['action' => $action]);
?>
<div class="jlog-index map-user">
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $cards,
            'columns' => [
                [
                    'label' => yii::t('map', 'User'),
                    'attribute' => 'user_id',
                    'value' => function($model) {
                        $userProfile = new UserProfile();

                        return $userProfile->findFlnameByUserId($model->user_id);
                    }
                ],
                [
                    'label' => yii::t('map', 'Circulation'),
                    'value' => function($model) use ($cards) {

                        return $cards->findLastCirculationByUserId($model->user_id);
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