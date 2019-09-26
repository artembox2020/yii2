<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\components\responsive\GridView;
use yii\widgets\Pjax;
use frontend\models\AddressBalanceHolder;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
    $query = $dataProvider->query;
    $data = $query->one();

    if ($data->imeiRelation->fakeAddress) {
        $data->imeiRelation->fakeAddress->initSerialNumber();
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
        <input 
            class = "address-serial-number"
            type = "text" 
            value = "<?= $data->imeiRelation->fakeAddress->displaySerialNumber() ?>"
        />
        <div class = "label">
            <?= Yii::t('frontend', 'Imei') ?>:
            <?= $data->imeiRelation->imei ?>
        </div>
    </div>
    <?php if ($data->imeiRelation->balanceHolder): ?>
    <a target = "_blank" href = "<?= '/net-manager/view-balance-holder?id='.$data->imeiRelation->balanceHolder->id ?>">
        <div class="col-md-5 col-sm-5 cell">
            <span><?= $data->imeiRelation->balanceHolder->name ?></span>
        </div>
    </a>
    <?php else: ?>
    <a target = "_blank">
        <div class="col-md-5 col-sm-5 cell">
            <span>
                <?=
                    (($fakeBalanceHolder = $data->imeiRelation->getFakeBalanceHolder()) ? $fakeBalanceHolder->name : '').
                    '<br>['.Yii::t('frontend', 'Deleted').']'
                ?>
            </span>
        </div>
    </a>
    <?php endif; ?>
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
            <input 
                type = "hidden" 
                class = "search-address-value"
                value = 
                "<?= 
                    mb_strtolower($data->imeiRelation->fakeAddress->address).(
                        !empty(($floor = $data->imeiRelation->fakeAddress->floor)) ? 
                        ', '.mb_strtolower($data->imeiRelation->fakeAddress->floor) : ''
                    );
                ?>"
            />
            <input
                type = "hidden"
                class = "address-id"
                value = "<?= $data->imeiRelation->fakeAddress->id ?>"
            />    
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