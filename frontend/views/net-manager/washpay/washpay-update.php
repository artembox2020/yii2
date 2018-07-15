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

?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
    <div class="imei-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'imei' => $imei,
            'addresses' => $addresses,
            'balanceHolders' => $balanceHolders
        ]) ?>

    </div>
