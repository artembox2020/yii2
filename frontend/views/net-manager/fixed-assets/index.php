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
/* @var $assets frontend\models\Imei */

?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<?php foreach ($assets as $imei) : ?>
    IMEI: <a href="/net-manager/fixed-assets-update?id=<?= $imei->id ?>"><b><?= $imei->imei ?></b></a><br>
<?php endforeach; ?>
