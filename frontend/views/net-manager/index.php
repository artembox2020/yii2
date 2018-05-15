<?php
/* @var $this yii\web\View */
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $addressBalanceHolders */
use yii\helpers\Html;

/* var address array */
$address = array();

/* var imei array */
$imei = array();

/* var machine array */
$machine = array();

/* var machine array */
$gd_machine = array();

?>
<h1>net-manager/index</h1>
<?php $menu = []; ?>
<b>
<?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>
<p>
<br>
    <?php echo Html::img('@web/storage/logos/' . $model->img . ''); ?><br>
    Название: <?= $model->name; ?><br>
    Адрес: <?= $model->address; ?><br>
    Описание: <?= $model->description; ?><br>
    Сайт: <?= $model->website; ?>
</p>
<p>
<br>
    <b><u>Технічні дані</u></b>
    <div>Кількість балансоутримувачив: <b><?= count($model->balanceHolders) ?></b></div>
    <?php foreach ($model->balanceHolders as $val) : ?>
        <?php $address[] = $val->addressBalanceHolders ?>
        <?php foreach ($val->addressBalanceHolders as $item) : ?>
            <?php $imei[] = $item->imeis ?>
            <?php foreach ($item->imeis as $wm) : ?>
                <?php $machine[] = $wm->wmMashine ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <div>Кількість адрес: <b><?= count($address) ?></b></div>
    <div>Кількість IMEI <b><?= count($imei) ?></b></div>
    <div>Кількість ПМ <b><?= count($machine) ?></b></div>
    <div>Кількість Дозаторів геля <b><?= count($wm->getGdMashine()) ?></b></div>
</p>
<p>
    <b><u>Фінансові дані</u></b>
</p>
