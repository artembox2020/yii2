<?php

/* @var $this yii\web\View */
?>
<!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'washing machines') ?><!--]</a>-->
<!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'Gel dispensers') ?><!--]</a>-->
<a href="/net-manager/fixed-assets">[<?= Yii::t('frontend', 'Storage') ?>]</a>
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
