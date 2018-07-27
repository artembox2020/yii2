<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $imei frontend\models\Imei */
/* @var $address frontend\models\AddressBalanceHolder */
/* @var $addresses frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $balanceHolders frontend\models\BalanceHolder */
/* @var $company frontend\models\Company */
/* @var $assets frontend\models\Imei */
/* @var $wm_machine frontend\models\WmMashine */
/* @var $imeis */

//Debugger::dd($imei);
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_button_back') ?>
</b><br>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
    <div class="imei-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_formWmMachine', [
//            'assets' => $assets,
            'company' => $company,
            'imeis' => $imeis,
            'imei' => $imei,
            'wm_machine' => $wm_machine,
//            'address' => $address,
            'addresses' => $addresses,
            'balanceHolder' => $balanceHolder,
            'balanceHolders' => $balanceHolders
        ]) ?>

    </div>
