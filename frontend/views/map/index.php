<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\CustomerCards;
use \frontend\models\AddressImeiData;
use \common\models\UserProfile;

/* @var $action int */
/* @var $cards backend\models\search\CardSearch */
/* @var $user common\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div  class="net-manager-new">
    <div class="jlog-index map-index">
        <?= 
            Yii::$app->view->render('/map/templates/shapter', ['action' => $action]);
        ?>

        <?= 
            Yii::$app->view->render('/map/templates/card_index', ['cards' => $cards, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>