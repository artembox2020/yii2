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
    <?php echo Html::img('@web/storage/logos/' . $model->img . ''); ?><br>
    Название: <?= $model->name; ?><br>
    Адрес: <?= $model->address; ?><br>
    Описание: <?= $model->description; ?><br>
    Сайт: <?= $model->website; ?><br/>
    <?= Html::a("[".Yii::t('frontend', 'Update')."]", ['company/update'], ['style' => 'background-color: #5c87b2; color: aliceblue']) ?>
</p>
<p>
<br>
    <b><u>Технічні дані</u></b>
    <div>Кількість балансоутримувачив: <b><?= $model->getCountBalanceHolder() ?></b></div>
    <div>Кількість адрес: <b><?= $model->getCountAddress() ?></b></div>
    <div>Кількість IMEI <b><?= $model->getCountImei() ?></b></div>
    <div>Кількість ПМ <b><?= $model->getCountWmMashine() ?></b></div>
    <div>Кількість Дозаторів геля <b><?= $model->getCountGdMashine(); ?></b></div>
</p>
<p>
    <b><u>Фінансові дані</u></b>
    <u>future</u>
</p>
