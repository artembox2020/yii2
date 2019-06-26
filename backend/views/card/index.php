<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use \frontend\models\CustomerCards;

$this->title = Yii::t('backend', 'Cards');
$this->params['breadcrumbs'][] = $this->title;

?>

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
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
        ],
    ],
]) ?>
