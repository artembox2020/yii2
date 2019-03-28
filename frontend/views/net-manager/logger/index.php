<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $addressBalanceHolders */
use yii\helpers\Html;
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('../_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>
