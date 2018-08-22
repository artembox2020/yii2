<?php

/* @var $this yii\web\View */
?>
<a href="/net-manager/index">[Компанія]</a>
<a href="/net-manager/employees">[Співробітники]</a>
<a href="/net-manager/balance-holders">[Балансотримачи]</a>
<a href="/net-manager/addresses">[Адреси]</a>
<a href="/net-manager/washpay">[WashPay]</a>
<a href="/net-manager/osnovnizasoby">[Основні засоби]</a>
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
