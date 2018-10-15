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
    global $serialNumber;
    if (empty($serialNumber)) {
        $serialNumber = 0;
    }
?>
<div class="row common-container">
    <div class= "common-header">
        <div class="col-md-2 col-sm-2 header">
            <?= Yii::t('frontend', 'Number') ?>
        </div>
        <div class="col-md-5 col-sm-5 header">
            <?= Yii::t('frontend', 'Balance Holder Name') ?>
        </div>
        <div class="col-md-5 col-sm-5 header">
            <?= Yii::t('frontend', 'Address') ?>
        </div>
    </div>

    <div class="col-md-2 col-sm-2 cell popup-block">
        <span><?= ++$serialNumber ?></span>
        <div class = "label">
            <?= Yii::t('frontend', 'Imei') ?>:
            <?= $data->imeiRelation->imei ?>
        </div>
    </div>
    <a target = "_blank" href = "<?= '/net-manager/view-balance-holder?id='.$data->imeiRelation->balanceHolder->id ?>">
        <div class="col-md-5 col-sm-5 cell">
            <span><?= $data->imeiRelation->balanceHolder->name ?></span>
        </div>
    </a>
    <a target = "_blank" href = "<?= '/address-balance-holder/view?id='. $data->imeiRelation->fakeAddress->id ?>">
        <div class="col-md-5 col-sm-5 cell popup-block">
            <div class = "label">
                <?= Yii::t('frontend', 'Address Name') ?>:
                <?= $data->imeiRelation->fakeAddress->name ?>
            </div>
            <span>
            <?= 
                $data->imeiRelation->fakeAddress->address.
                (
                    !empty(($floor = $data->imeiRelation->fakeAddress->floor)) ?
                    '<br>('.Yii::t('frontend', 'floor').':'.$floor.')' : ''
                )
            ?>
            </span>
            <br/><br/><!--
            <div class="row common-container-block">
                <div class= "common-header">
                    <div class="header">
                        <?= Yii::t('frontend', 'Address Name') ?>
                    </div>
                </div>
                <br>
                <span><?= $data->imeiRelation->fakeAddress->name ?></span>
            </div>-->    
        </div>
    </a>
</div>