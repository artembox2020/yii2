<?php

use yii\helpers\Html;
use frontend\components\responsive\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ImeiDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Imei Datas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imei-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create Imei Data'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'imei_id',
            'created_at',
            'imei',
            'level_signal',
            //'on_modem_account',
            //'in_banknotes',
            //'money_in_banknotes',
            //'fireproof_residue',
            //'price_regim',
            //'updated_at',
            //'is_deleted',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
