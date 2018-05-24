<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
/* @var $addresses */
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<?php foreach ($balanceHolders as $item) : ?>
    <?php foreach ($item->addressBalanceHolders as $address) : ?>
        <?= $item->name ?>
            Адрес: <a href=""><b><?= $address->address; ?></b></a>
            Этаж: <?= $address->floor ?><br>
        <?php foreach ($address->imeis as $imei) : ?>
            <?= $item->name ?>
            Адрес: <a href=""><b><?= $address->address; ?></b></a>
            Этаж: <?= $address->floor ?>
            Imei:
            <?php if (!isset($address->imeis) or !empty($imei->imei)) : ?>
            <?= $imei->imei ?>
            <?php elseif($imei->imei == "") : ?>
                <a href="/imei/create"><b>додати IMEI</b></a>
            <?php else : ?>
                <a href="/imei/create"><b>додати IMEI</b></a>
            <?php endif; ?>
            <br>
        <?php endforeach;?><br>
<?php endforeach;?>
<?php endforeach;?>
<b><a href="/address-balance-holder/create">[додати адресу]</a></b>

