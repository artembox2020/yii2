<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<?php foreach ($balanceHolders as $item) : ?>
    <?= $item->name ?>
    <?= $item->address ?>
    tel.<?= $item->phone ?>
    contact person.<?= $item->contact_person ?> <?= Html::a(Yii::t('frontend', 'Add Address'), ['/address-balance-holder/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('frontend', 'view'), ['net-manager/view-balance-holder', 'id' => $item->id]) ?>
    <br>
    <?php $address[] = $item->addressBalanceHolders ?>
    <?php foreach ($item->addressBalanceHolders as $value) : ?>
        <?php $imei[] = $value->imeis ?>
        <?php foreach ($value->imeis as $wm) : ?>
            <?php $machine[] = $wm->wmMashine ?>
            <?php $gd_machine[] = $wm->gdMashine ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endforeach;?>

<p><b><a href="/balance-holder/create">Додати балансотримача</a></b></p>

<p><u><b>Зведені технічні данні</b></u><p/>
<div>Кількість адрес: <b><?= count($address) ?></b></div>
<div>Кількість IMEI: <b><?= count($imei) ?></b></div>
<div>Кількість пральних машин: <b><?= count($machine) ?></b></div>
<div>Кількість Дозаторів геля: <b><?= count($address) ?></b></div>
<div>Останні помилки: <b><u>future</u></b></div>
<div>Oстанні ремонти: <b><u>future</u></b></div>