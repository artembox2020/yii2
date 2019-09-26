<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\components\responsive\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Imei */
/* @var $model frontend\models\BalanceHolder */
/* @var $model frontend\models\Company */
/* @var $address */
?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        [
            'attribute' => 'img',
            'format' => 'html',
            'value' => function ($data) {
                return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['img'],['max-width' => '80px']));
            },
        ],
        'description:ntext',
        'website',
    ],
    ]) 
    ?>