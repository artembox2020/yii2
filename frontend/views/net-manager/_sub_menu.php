<?php

/* @var $this yii\web\View */

if (Yii::$app->layout == 'main-new') {
    echo Yii::$app->view->render('/net-manager/_sub_menu-new-body');
} else { ?>
    <a href="/net-manager/index">[<?= Yii::t('frontend', 'Company') ?>]</a>

    <a href="/net-manager/employees">[<?= Yii::t('frontend', 'Employees') ?>]</a>

    <a href="/net-manager/balance-holders">[<?= Yii::t('frontend', 'Balance Holders') ?>]</a>

    <a href="/net-manager/addresses">[<?= Yii::t('frontend', 'Addresses') ?>]</a>

    <a href="/net-manager/washpay">[WashPay]</a>

    <a href="/net-manager/modem-history">[<?= Yii::t('frontend', 'Modem History') ?>]</a>

    <a href="/net-manager/logger">[<?= Yii::t('frontend', 'Logger') ?>]</a>

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
<?php } ?>