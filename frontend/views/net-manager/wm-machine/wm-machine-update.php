<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $imei frontend\models\Imei */
/* @var $address frontend\models\AddressBalanceHolder */
/* @var $addresses frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $balanceHolders frontend\models\BalanceHolder */
/* @var $company frontend\models\Company */
/* @var $model frontend\models\WmMashine */
/* @var $technical_work frontend\models\TechnicalWork */
/* @var $imeis */


?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
    <div class="imei-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_update', [
            'model' => $model,
            'company' => $company,
            'imeis' => $imeis,
            'addresses' => $addresses,
            'technical_work' => $technical_work
        ]) ?>

    </div>
