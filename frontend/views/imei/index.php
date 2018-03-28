<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ImeiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Imeis');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imei-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create Imei'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'imei',
            'address_id',
            'type_packet',
            'imei_central_board',
            //'firmware_version',
            //'type_bill_acceptance',
            //'serial_number_kp',
            //'phone_module_number',
            //'crash_event_sms',
            //'critical_amount',
            //'time_out:datetime',
            //'created_at',
            //'updated_at',
            //'is_deleted',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
