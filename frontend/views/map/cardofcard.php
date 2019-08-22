<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\Transactions;
use backend\models\search\CardSearch;

/* @var $action int */
/* @var $card frontend\models\CustomerCards */
/* @var $transactionSearch backend\models\search\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $transaction frontend\models\Transactions */
/* @var $userProfile common\models\UserProfile */

?>

<?= Yii::$app->view->render('/map/templates/update-map-alert') ?>

<div class="monitoring-new">
    <div class="jlog-index map-index">
        <?=
            Yii::$app->view->render('/map/templates/shapter', ['action' => $action]);
        ?>
        
        <?=
            Yii::$app->view->render(
                '/map/templates/card_info',
                [
                    'card' => $card,
                    'userProfile' => $userProfile,
                    'transaction' => $transaction
                ]
            );
        ?>
        <span class="text-center fw600">
            <h3><?= Yii::t('map', 'Use History') ?></h3>
        </span>
        <?= 
            Yii::$app->view->render(
                '/map/templates/transaction_index',
                [
                    'dataProvider' => $dataProvider,
                    'transactionSearch' => $transactionSearch
                ]
            );
        ?>
    </div>
</div>