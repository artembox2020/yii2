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
    <font size="4"><?= Yii::t('frontend', 'Name Company') ?>:</font>&#8195;<?= $model->name; ?><br>
    <font size="4"><?= Yii::t('frontend', 'Address') ?>:</font>&#8195;<?= $model->address; ?><br>
    <font size="4"><?= Yii::t('frontend', 'Description') ?>:</font>&#8195;<?= $model->description; ?><br>
    <font size="4"><?= Yii::t('frontend', 'Site') ?>:</font>&#8195;<?= $model->website; ?><br/>
    <?= Html::a("[".Yii::t('frontend', 'Update')."]", ['company/update'], ['style' => 'background-color: #5c87b2; color: aliceblue']) ?>
</div>
</p>
<p>
<br>
<div class = "company-summary-block">
    <b><u><?= Yii::t('frontend', 'Тechnical Data') ?></u></b>
    <div><font size="4"><?= Yii::t('frontend', 'Count Balance Holders') ?>:</font>&#8195;<b><?= $model->getCountBalanceHolder() ?></b></div>
    <div><font size="4"><?= Yii::t('frontend', 'Count Addresses') ?>:</font>&#8195;<b><?= $model->getCountAddress() ?></b></div>
    <div><font size="4"><?= Yii::t('frontend', 'Count Imeis') ?>:</font>&#8195;<b><?= $model->getCountImei() ?></b></div>
    <div><font size="4"><?= Yii::t('frontend', 'Count Wash Machine') ?>:</font>&#8195;<b><?= $model->getCountWmMashine() ?></b></div>
    <div><font size="4"><?= Yii::t('frontend', 'Count Gd Machine') ?>:</font>&#8195;<b><?= $model->getCountGdMashine(); ?></b></div>
</div>    
</p>
<p>
    <b><u><?= Yii::t('frontend', 'Financial Data') ?></u></b>
    <u>future</u>
</p>
