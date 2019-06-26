<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\Transactions;

$this->title = Yii::t('backend', 'Cards');
$this->params['breadcrumbs'][] = ['label' => yii::t('backend', 'Cards'), 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $transactions,
    'columns' => [
        'imei',
        [
            'label' => yii::t('payment', 'Operation'),
            'attribute' => 'operation',
            'value' => function($transactions) {
                return Transactions::statuses($transactions->operation);
            },
            'filter' => Transactions::statuses(),
        ],
        [
            'label' => yii::t('payment', 'amount'),
            'attribute' => 'amount'
        ],
        [
            'label' => yii::t('payment', 'operation time'),
            'attribute' => 'operation_time',
        ],
    ],
]) ?>
