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
</b>
<!--<div>-->
<!--    Серийный номер:-->
<!--    Адрес:-->
<!--    Балансотримач:-->
<!--    Останнiй пiнг:-->
<!--</div>-->
<br><br>
<?php foreach ($balanceHolders as $item) : ?>
    <?php foreach ($item->addressBalanceHolders as $address) : ?>
        <?php foreach ($address->imeis as $imei) : ?>
<?php
$lastCount = $imei->getMachineStatus()->orderBy('created_at DESC')->where('created_at >= CURDATE()')->count();
$count = $imei->getMachineStatus()->select('number_device')->distinct()->limit($lastCount)->count();
$machines = $imei->getMachineStatus()->orderBy('number_device DESC')->addOrderBy('number_device')->limit($count)->all();?>
<?php foreach ($machines as $machine) : ?>
    <a href="/net-manager/wm-machine-view?number_device=<?= $machine->number_device ?>">CM</a> <?= $machine->number_device ?>
                <div>
                    Серийный номер: <?= $machine->serial_number ?> | n/a<br>
                    Адрес: <?= $address->name ?><br>
                    Балансотримач: <?= $item->name ?><br>
                    Останнiй пiнг: <?= Yii::$app->formatter->asDate($machine->updated_at, 'dd.MM.yyyy H:i:s');?>
                </div>

<?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach;?>
<?php endforeach; ?><br>
<b><a href="wm-machine-create">[додати WM]</a></b>
