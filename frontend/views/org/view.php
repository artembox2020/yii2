<?php

use yii\helpers\Html;
use frontend\components\responsive\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Org */
?>
<div class="org-view">
    <p>
        <?php  if(Yii::$app->user->can('editCompanyData')) { echo Html::a(Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);} ?>
        <?php  if(Yii::$app->user->can('editCompanyData')) { echo  Html::a(Yii::t('frontend', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ;} ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name_org',
            [
				'attribute' => 'logo_path',
				'format' => 'html',    
				'value' => function ($data) {
					return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['logo_path'],['max-width' => '80px']));
				},
			],
            'desc:ntext',
            'user_id',
        ],
    ]) ?>
<h1><?=Yii::t('DashboardModule.base', 'Автоматы принадлежащие организации')?></h1>
	 <table class="table"> 
        <thead> <tr>  <th>IMEI</th> <th>Data</th> </tr> </thead> 
        <tbody> 
    <?php 
        if(isset($devices) AND is_array($devices)){

            foreach ($devices as $device){
				
    ?>

            <tr> 
				<th scope="row">
					<?=$device->id_dev?>
				</th> 
				<td>
					<b><?=stripslashes($device->name)?></b> 
					<?=stripslashes($device->organization)?> <?=stripslashes($device->city)?> <?=stripslashes($device->adress)?>
				</td> 
            </tr>     
    <?php
            }
        }
    
    ?>

           
        </tbody> 
    </table>
</div>
