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
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<?php foreach ($balanceHolders as $balanceHolder) : ?>
    <?php foreach ($balanceHolder->addressBalanceHolders as $address) : ?>
        <?php foreach ($address->imeis as $imei) : ?>
            IMEI: <a href="/net-manager/washpay-view?id=<?= $imei->id ?>"><b><?= $imei->imei ?></b></a>
            Адреса: <?= $address->address ?>
            Балансоутримувач: <?= $balanceHolder->name ?>
            Останній пінг: <?php if ($imei->getInit() == 'Ok') : ?>
                <?= Yii::$app->formatter->asDate($imei->updated_at, 'dd.MM.yyyy H:i:s'); ?>
            <?php else : ?>
                <?= $imei->getInit(); ?>
            <?php endif; ?>
            <br>
        <?php endforeach; ?>
    <?php endforeach;?>
<?php endforeach; ?>
<br>
<b><a href="washpay-create">[додати WASHPAY]</a></b>
