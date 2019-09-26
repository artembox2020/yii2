<?php

use yii\helpers\Html;
use frontend\components\responsive\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AddressBalanceHolderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Address Balance Holders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-balance-holder-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create Address Balance Holder'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            ['attribute' => 'balanceHolder.name',
                'label' => Yii::t('frontend', 'Balance Holder'),],
            ['attribute' => 'address',
                'label' => Yii::t('frontend', 'Address'),],
            ['attribute' => 'floor',
                'label' => Yii::t('frontend', 'Floor'),],
            ['attribute' => 'imei.imei',
                'label' => Yii::t('frontend', 'Imei'),],
//            'date_inserted:date',
//            'date_connection_monitoring:date',
            //'created_at',
            //'updated_at',
            //'is_deleted',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
