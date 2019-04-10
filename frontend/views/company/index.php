<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create Company'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('frontend', 'Restore Company'), ['restore'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'img',
                'format' => 'html',
                'contentOptions' =>['style'=>'text-align: center;vertical-align: middle;'],
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['img']));
                },
            ],
            'description:ntext',
            'website',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
