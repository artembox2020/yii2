<?php
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
<?php $menu = []; ?>
<b>
<?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>
<p>
<br>
<div class = "company-summary-block">
    <?php echo Html::img('@web/storage/logos/' . $model->img . ''); ?><br>
    <font size="4">Название:</font>&#8195;<?= $model->name; ?><br>
    <font size="4">Адрес:</font>&#8195;<?= $model->address; ?><br>
    <font size="4">Описание:</font>&#8195;<?= $model->description; ?><br>
    <font size="4">Сайт:</font>&#8195;<?= $model->website; ?><br/>
    <?= Html::a("[".Yii::t('frontend', 'Update')."]", ['company/update'], ['style' => 'background-color: #5c87b2; color: aliceblue']) ?>
</div>
</p>
<p>
<br>
<div class = "company-summary-block">
    <b><u>Технічні дані</u></b>
    <div><font size="4">Кількість балансоутримувачив:</font>&#8195;<b><?= $model->getCountBalanceHolder() ?></b></div>
    <div><font size="4">Кількість адрес:</font>&#8195;<b><?= $model->getCountAddress() ?></b></div>
    <div><font size="4">Кількість IMEI:</font>&#8195;<b><?= $model->getCountImei() ?></b></div>
    <div><font size="4">Кількість ПМ:</font>&#8195;<b><?= $model->getCountWmMashine() ?></b></div>
    <div><font size="4">Кількість Дозаторів геля:</font>&#8195;<b><?= $model->getCountGdMashine(); ?></b></div>
</div>    
</p>
<p>
    <b><u>Фінансові дані</u></b>
    <u>future</u>
</p>
