<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'img',
            'description:ntext',
            'website',
            //'is_deleted',
            //'deleted_at',

            [
                    'class' => 'yii\grid\ActionColumn',
                'template' => '{company-restore}',
                'buttons' => [
                    'company-restore' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-floppy-open"></span>', $url, [
                            'title' => Yii::t('backend', 'Restore'),
                        ]);
                    },
                    ],
                ],
        ],
    ]); ?>
</div>
