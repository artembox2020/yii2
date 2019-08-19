<?php

/* @var $action int */
/* @var $cards backend\models\search\CardSearch */
/* @var $transactionSearch backend\models\search\TransactionSearch */
/* @var $user common\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $transactionDataProvider yii\data\ActiveDataProvider */

?>
<div class="net-manager-new">
    <div class="jlog-index map-user">
        <?=
            Yii::$app->view->render('/map/templates/shapter', ['action' => $action]);
        ?>
        <?=
            Yii::$app->view->render(
                '/map/templates/user_info',
                [
                    'cards' => $cards,
                    'user' => $user
                ]
            );
        ?>
        <span class="text-left" style="font-size: 1.75rem">
            <h3 align=center><?= Yii::t('map', 'User Cards') ?></h3>
        </span>
        <?=
            Yii::$app->view->render('/map/templates/card_index', ['cards' => $cards, 'dataProvider' => $dataProvider]);
        ?>
        <span class="text-center fw600"><h3><?= Yii::t('map', 'Use History') ?></h3></span>
        <?=
            Yii::$app->view->render(
                '/map/templates/transaction_index',
                [
                    'dataProvider' => $transactionDataProvider,
                    'transactionSearch' => $transactionSearch
                ]
            );
        ?>
    </div>
</div>