<?php

use yii\helpers\Html;
use frontend\components\responsive\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'imei',
            'gsmSignal',
            'fvVer',
            'numBills',
            'billAcceptorState',
            'id_hard',
            'type',
            'collection',
            'ZigBeeSig',
            'billCash',
            'tariff',
            'event',
            'edate',
            'billModem',
            'sumBills',
            'ost',
            'numDev',
            'devSignal',
            'statusDev',
            'colGel',
            'colCart',
            'price',
            'timeout',
            'doorpos',
            'doorled',
            'kpVer',
            'srVer',
            'mTel',
            'sTel',
            'ksum',

        ],
    ]); ?>
</div>
