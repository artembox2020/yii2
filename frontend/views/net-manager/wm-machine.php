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
    <?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<?php foreach ($balanceHolders as $item) : ?>
    <?php foreach ($item->addressBalanceHolders as $address) : ?>
        <?php foreach ($address->imeis as $imei) : ?>
            IMEI: <?= $imei->imei ?><br>
<?php
$lastCount = $imei->getMachineStatus()->orderBy('created_at DESC')->where('created_at >= CURDATE()')->count();
$count = $imei->getMachineStatus()->select('number_device')->distinct()->limit($lastCount)->count();
$machines = $imei->getMachineStatus()->orderBy('number_device DESC')->addOrderBy('number_device')->limit($count)->all();?>
<?php foreach ($machines as $machine) : ?>
    CM <?= $machine->number_device ?>
    (status: <?php if (array_key_exists($machine->status, $machine->current_status)): ?>
        <?php $machine->status = $machine->current_status[$machine->status] ?>
        <?= Yii::t('frontend', $machine->status) ?>
    <?php endif; ?>)
<?php endforeach; ?><br>
        <?php endforeach; ?>
    <?php endforeach;?>
<?php endforeach; ?>
