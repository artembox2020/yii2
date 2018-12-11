<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;

/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders frontend\models\BalanceHolder */
/* @var $addresses */
/* @var $imeis frontend\models\Imei */
/* @var $wm_machines frontend\models\WmMashine */

?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>

    <!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'washing machines') ?><!--]</a>-->
    <!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'Gel dispensers') ?><!--]</a>-->
    <br><br>
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

</b><br><br>
<?php foreach ($imeis as $imei) : ?>
    IMEI: <a href="/net-manager/fixed-assets-update-imei?id=<?= $imei->id ?>"><b><?= $imei->imei ?></b></a><br>
<?php endforeach; ?>

<?php foreach ($wm_machines as $wm_machine) : ?>
    Wm Machine: <a href="/net-manager/fixed-assets-update-wm-machine?id=<?= $wm_machine->id ?>"><b> id:<?= $wm_machine->id ?></b></a><br>
<?php endforeach; ?>
