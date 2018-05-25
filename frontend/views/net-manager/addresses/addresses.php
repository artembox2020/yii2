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
        <?= $item->name . ' ' . $item->address?>
        Адрес: <a href="/address-balance-holder/view?id=<?= $address->id ?>"><b><?= $address->address; ?></b></a>
        Этаж: <?= $address->floor ?>
    <?php if (!empty($address->imeis)) : ?>
        <?php foreach ($address->imeis as $imei) : ?>
            Imei:
            <?php if (!empty($imei->imei)) : ?>
            <?= $imei->imei ?>
            <?php else : ?>
            <a href="/imei/create"><b>додати IMEI</b></a>
            <?php endif; ?>
            <br>
        <?php endforeach;?><br>
        <?php else : ?>
            <a href="/imei/create"><b>додати IMEI</b></a><br>
        <?php endif; ?>
    <?php endforeach;?>
<?php endforeach;?>
<br>
<b><a href="/address-balance-holder/create">[додати адресу]</a></b>

