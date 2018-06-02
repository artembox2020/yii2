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
/* @var $model frontend\models\WmMashine */
/* @var $imes */


?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
    <div class="imei-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
                'model' => $model,
            'company' => $company,
            'imeis' => $imeis
        ]) ?>

    </div>
