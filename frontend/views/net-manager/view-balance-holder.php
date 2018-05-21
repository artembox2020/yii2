<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<p><u><b>Балансотримач</b></u><p/>
<div>Назва: <b><?=  $model->name ?></b></div>
<div>Адреса балансотримача: <b><?= $model->address ?></b></div>
<div>Дата початку співпраці: <b><?= $model->date_start_cooperation ?></b></div>
<div>Дата підключення до моніторінга: <b><?= $model->date_connection_monitoring ?></b></div>
<div>Контактна особа: <b><u><?= $model->contact_person ?></u></b></div>
<div>Посада: <b><u><?= $model->position ?></u></b></div>
<div>Телефон: <b><u><?= $model->phone ?></u></b></div>
<div>Администрирование: <b><u>future</u></b></div>
