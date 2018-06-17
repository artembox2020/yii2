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
    <?= $this->render('/net-manager/_sub_menu', [
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
<br>
<div><b><u>Iншi контактнi особи</u></b> <b><a href="/other-contact-person/"><?= Yii::t('frontend', 'Update') ?></a></b></div>
<?php foreach ($model->otherContactPerson as $contact) : ?>
    <?= $contact->name; ?>
    <?= $contact->position; ?>
    <?= $contact->phone; ?><br>
<?php endforeach; ?>
<br>
<?php if (count($model->otherContactPerson) < 10) : ?>
<div><a href="/other-contact-person/create">добавить контактну особу</a> (Лимит 10)</div>
<?php endif; ?>
