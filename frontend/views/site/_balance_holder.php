<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model frontend\models\Imei */
/* @var $model frontend\models\BalanceHolder */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $address */
?>
<b><?= Yii::t('frontend', 'BalanceHolders'); ?></b> <?= Html::a(Yii::t('frontend', 'Add Balance Holder'), ['/balance-holder', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
<?php foreach ($balanceHolders as $item) : ?>
<?php foreach ($item->addressBalanceHolders as $address) : ?>
                <?php $form = ActiveForm::begin([
                    'action' => '/imei/create'
                ]) ?>
                <?= $address->address ?>
                Этаж: <?= $address->floor ?>
                <?=  Html::hiddenInput('address_id', $address->id); ?>
                <?= Html::submitButton(Yii::t('frontend', 'Add IMEI'), ['class' => 'btn btn-success']) ?><br>
                <?php ActiveForm::end(); ?>
            <?= $item->name ?>
            <?= $item->address ?>
            tel.<?= $item->phone ?>
            contact person.<?= $item->contact_person ?> <?= Html::a(Yii::t('frontend', 'Add Address'), ['/address-balance-holder', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
<?php endforeach; ?>
<?php endforeach; ?>