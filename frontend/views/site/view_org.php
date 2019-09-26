<?php

use yii\helpers\Html;
use frontend\components\responsive\DetailView;
use frontend\models\Org;

$this->title = $model->name_org;
?>
<div class="account-default-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php if ($model->logo_path) : ?>
            <img src="<?= Yii::getAlias('@storageUrl/logos/' . $model->logo_path) ?>" class="img-thumbnail" alt>
        <?php else: ?>
            <img src="<?= Yii::$app->homeUrl . '/static/img/default_company.png' ?>" class="img-thumbnail" alt>
        <?php endif ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => Yii::t('frontend', 'Organization'),
                'value' => $model->name_org,
                'visible' => $model->name_org !== null,
            ],
            [
                'attribute' => Yii::t('frontend', 'Description'),
                'value' => $model->desc,
                'visible' => $model->desc !== null,
            ],
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
