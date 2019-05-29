<?php

/* @var $this yii\web\View */
?>
<?php if ( yii::$app->user->can('viewCompanyData') ) { ?>
    <a href="/net-manager/index">[<?= Yii::t('frontend', 'Company') ?>]</a>
<?php } ?>

<?php if ( yii::$app->user->can('viewCompanyData') ) { ?>
    <a href="/net-manager/employees">[<?= Yii::t('frontend', 'Employees') ?>]</a>
<?php } ?>

<?php if ( yii::$app->user->can('editFinData') ) { ?>
    <a href="/net-manager/balance-holders">[<?= Yii::t('frontend', 'Balance Holders') ?>]</a>
<?php } ?>

<?php if ( yii::$app->user->can('viewFindata') ) { ?>
    <a href="/net-manager/addresses">[<?= Yii::t('frontend', 'Addresses') ?>]</a>
<?php } ?>

<?php if ( yii::$app->user->can('viewFinData') ) { ?>
    <a href="/net-manager/washpay">[WashPay]</a>
<?php } ?>

<?php if ( yii::$app->user->can('viewTechData') ) { ?>
    <a href="/net-manager/modem-history">[<?= Yii::t('frontend', 'Modem History') ?>]</a>
<?php } ?>

<?php if ( yii::$app->user->can('viewTechData') ) { ?>
    <a href="/net-manager/logger">[<?= Yii::t('frontend', 'Logger') ?>]</a>
<?php } ?>

<a href="/net-manager/osnovnizasoby">[<?= Yii::t('frontend', 'Fixed Assets') ?>]</a>
<!--<a href="/net-manager/fixed-assets">[Відключені]</a>-->
<?php
$url = \yii\helpers\BaseUrl::current();
$url = explode("?", $url)[0];
$script = <<< JS
    var selector = document.querySelector("[href='{$url}']");
    if (typeof selector !== "undefined" && selector !== null) {
        selector.style.color = 'green';
    }
JS;
$this->registerJs($script);
?>
