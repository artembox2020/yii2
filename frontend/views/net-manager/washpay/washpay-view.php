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
/* @var $address */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $imei frontend\models\Imei */

?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
IMEI: <?= $imei->imei ?><br>
Номер телефона: <?= $imei->phone_module_number ?><br>
Балансотримач: <?= $balanceHolder->name . ' ' . $balanceHolder->address; ?><br>
Адреса/поверх: <?= $address->address ?><br>
Версія плати: <?= $imei->firmware_version ?><br>
Версія бутлоадера / дата: <?= $imei->type_bill_acceptance ?> | Дата - Откуда?<br>
Версія основной прошивки / дата: <?= $imei->type_bill_acceptance ?> | Дата - Откуда?<br>
Последний пинг: <?php if ($imei->getInit() == 'Ok') : ?>
                    <?= Yii::$app->formatter->asDate($imei->updated_at, 'dd.MM.yyyy H:i:s'); ?>
                <?php else : ?>
                    <?= $imei->getInit(); ?>
                <?php endif; ?>
<br>
Статус: <?php if (array_key_exists($imei->status, $imei->current_status)): ?>
    <?php $imei->status = $imei->current_status[$imei->status] ?>
    <?= Yii::t('frontend', $imei->status) ?>
<?php endif; ?><br>
<div><b><a href="/net-manager/washpay-update?id=<?= $imei->id ?>"><?= Yii::t('frontend', 'Update') ?></a></b></div>
<br>
<div>
    <b><u>Історія</u></b><br>
    Кількість циклів прання: 23454<br>
    Час роботи: 346567<br>
    Кількість грошей: 45665<br>
    Останні помилки: список<br>
</div>
