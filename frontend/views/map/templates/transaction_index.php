<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\Transactions;
use backend\models\search\CardSearch;

/* @var $transactionSearch backend\models\search\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="map-cardofcard transaction-index">
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $transactionSearch,
            'columns' => [
                [
                    'label' => Yii::t('map', 'Date'),
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d-m-Y'],
                ],
                [
                    'label' => Yii::t('map', 'Time'),
                    'attribute' => 'created_at',
                    'value' => function($model) {

                        return date('H:i', $model->created_at);
                    }
                ],
                [
                    'label' => Yii::t('map', 'Card'),
                    'headerOptions' => ['class' => 'card'],
                    'contentOptions' => ['class' => 'card'],
                    'attribute' => 'card_no',
                    'format' => 'raw',
                    'value' => function($model) {

                        return \yii\helpers\Html::a($model->card_no, '/map/cardofcard?cardNo='.$model->card_no);
                    }
                ],
                [
                    'label' => yii::t('payment', 'Operation'),
                    'attribute' => 'operation',
                    'value' => function($transaction) {

                        return Transactions::statuses($transaction->operation);
                    },
                    'filter' => Transactions::statuses(),
                ],
                [
                    'label' => yii::t('map', 'Amount'),
                    'attribute' => 'amount'
                ],
                [
                    'label' => yii::t('map', 'Address'),
                    'format' => 'raw',
                    'value' => function($transaction) {
                        $cardSearch = new CardSearch();

                        return $cardSearch->findAddressByCardNo($transaction->card_no, $transaction);
                    }
                ],
                [
                    'label' => yii::t('map', 'Additional Info'),
                    'value' => function($transaction) {

                        return $transaction->comment;
                    }
                ],
            ],
        ]) ?>
    </div>
</div>