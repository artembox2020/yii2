<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
    $query = $dataProvider->query;
    $data = $query->one();
?>
<div class="row common-container">
    <div class= "common-header">
        <div class="col-md-4 col-sm-4 header">
            <?= Yii::t('frontend', 'ID') ?>
        </div>
        <div class="col-md-4 col-sm-4 header">
            <?= Yii::t('frontend', 'Balance Holder Name') ?>
        </div>
        <div class="col-md-4 col-sm-4 header">
            <?= Yii::t('frontend', 'Address') ?>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 cell">
        <span><?= $data->imeiRelation->id ?></span>
    </div>
    <div class="col-md-4 col-sm-4 cell">
        <span><?= $data->imeiRelation->balanceHolder->name ?></span>
    </div>
    <div class="col-md-4 col-sm-4 cell">
        <span><?= $data->imeiRelation->address->address ?></span>
        <br/><br/>
        <div class="row common-container-block">
            <div class= "common-header">
                <div class="header">
                    <?= Yii::t('frontend', 'Address Name') ?>
                </div>
            </div>
            <br>
            <span><?= $data->imeiRelation->address->name ?></span>
        </div>    
    </div>
</div>