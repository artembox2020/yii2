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
    <?= $address->name; ?><br>
<?php endforeach;?>
<?php endforeach; ?>
