<?php

use yii\helpers\Html;
use frontend\components\responsive\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrgSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="org-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php 
			if(Yii::$app->user->can('editCompanyData')){
				echo Html::a(Yii::t('frontend', 'Создать организацию'), ['create'], ['class' => 'btn btn-success']);
			}
		?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'name_org',
				'contentOptions' =>['style'=>'text-align: center;vertical-align: middle;'],
				'format' => 'html',    
				'value' => function ($data) {
					return $data['name_org'];
				},
			],
			[
				'attribute' => 'image',
				'format' => 'html',    
				'contentOptions' =>['style'=>'text-align: center;vertical-align: middle;'],
				'value' => function ($data) {
					return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['logo_path']));
				},
			],
			[
				'attribute' => 'desc',
				'contentOptions' =>['style'=>'text-align: center;vertical-align: middle;'],
				'format' => 'html',    
				'value' => function ($data) {
					return $data['desc'];
				},
			],
			[
				'attribute' => 'user_id',
				'contentOptions' =>['style'=>'text-align: center;vertical-align: middle;'],
				'format' => 'html',    
				'value' => function ($data) {
					return $data['user_id'];
				},
			],

            //'admin_id',

            ['class' => 'yii\grid\ActionColumn', 'contentOptions' =>['style'=>'text-align: center;vertical-align: middle;'],],
        ],
    ]); ?>
</div>
