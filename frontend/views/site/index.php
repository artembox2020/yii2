<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */


$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Update'), ['/company/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--         Html::a(Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [-->
<!--            'class' => 'btn btn-danger',-->
<!--            'data' => [-->
<!--                'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),-->
<!--                'method' => 'post',-->
<!--            ],-->
<!--        ]) -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'img',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['img'],['max-width' => '80px']));
                },
            ],
            'description:ntext',
            'website',
        ],
    ]) ?>
    <b><?= Yii::t('frontend', 'Employees company') ?></b> <?= Html::a(Yii::t('frontend', 'Add Employee'), ['/account/default/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    <p>
        <?php foreach ($users as $user) : ?>
            <?= $user->username . ' ' .
            Yii::t('frontend','Role') . ': ' .
            Yii::t('frontend', $user->getUserRoleName($user->id)); ?> <br>
        <?php endforeach; ?>
    </p>
    <b><?= Yii::t('frontend', 'BalanceHolders'); ?></b> <?= Html::a(Yii::t('frontend', 'Add Balance Holder'), ['/balance-holder', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    <p>
        <?php foreach ($balanceHolders as $item) : ?>
            <?= $item->name ?>
            <?= $item->address ?>
            tel.<?= $item->phone ?>
            contact person.<?= $item->contact_person ?> <?= Html::a(Yii::t('frontend', 'Add Address'), ['/address-balance-holder', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <p>
            <?php foreach ($item->addressBalanceHolders as $address) : ?>
                <?php $form = ActiveForm::begin([
                    'action' => '/imei/create'
                ]) ?>
                <?= $address->address ?>
                Этаж: <?= $address->floor ?>
                <?=  Html::hiddenInput('address_id', $address->id); ?>
                <?= Html::submitButton(Yii::t('frontend', 'Add IMEI'), ['class' => 'btn btn-success']) ?><br>
                <?php ActiveForm::end(); ?>
                <?php foreach ($address->imeis as $imei) : ?>
                    IMEI: <?= $imei->imei ?>
                    <?php if (!empty($imei->firmware_version)) : ?>
                    <?php echo 'Init: <b style="color: forestgreen">Ok</b>'; ?> <?= date('d.m.Y', $imei->updated_at); ?>
                        <?php $form = ActiveForm::begin([
                                'action' => '/wm-mashine/create'
                            ]) ?>
                        <?=  Html::hiddenInput('imei_id', $imei->id); ?>
                        <?= Html::submitButton(Yii::t('frontend', 'Add WM Machine'), ['class' => 'btn btn-success']) ?>
                    <?php ActiveForm::end(); ?>
                        <br>
                        <?php $form = ActiveForm::begin([
                            'action' => 'gd-mashine/create'
                        ]) ?>
                        <?=  Html::hiddenInput('imei_id', $imei->id); ?>
                        <?= Html::submitButton(Yii::t('frontend', 'Add GD Machine'), ['class' => 'btn btn-success']) ?>
                        <?php ActiveForm::end(); ?>
                        <br>
                        <?php 
                        $lastCount = $imei->getMachineStatus()->orderBy('created_at DESC')->where('created_at >= CURDATE()')->count();
                        $count = $imei->getMachineStatus()->select('number_device')->distinct()->limit($lastCount)->count();
                        $machines = $imei->getMachineStatus()->orderBy('number_device DESC')->addOrderBy('number_device')->limit($count)->all();?>
                        <?php foreach ($machines as $machine) : ?>
                            CM <?= $machine->number_device ?>
                            (status: <?php if (array_key_exists($machine->status, $machine->current_status)): ?> 
                            <?php $machine->status = $machine->current_status[$machine->status] ?>
                            <b><?= Yii::t('frontend', $machine->status) ?></b>
                            <?php endif; ?>)
                        <?php endforeach; ?><br>
                        <?php if ($imei->getGdMashine()->orderBy('id DESC')->one()): ?>
                        <?php $gd_machine = $imei->getGdMashine()->orderBy('id DESC')->one(); ?>
                        TYPE: <?= $gd_machine->type_mashine; ?>
                        GEL IN TANK: <?= $gd_machine->gel_in_tank; ?>
                        BILL CASH: <?= $gd_machine->bill_cash ?>
                        STATUS: <?php if (array_key_exists($gd_machine->status, $gd_machine->current_status)): ?> 
                            <?php $gd_machine->status = $gd_machine->current_status[$gd_machine->status] ?>
                            <?= Yii::t('frontend', $gd_machine->status) ?>
                            <?php endif; ?><br>
                        <?php endif; ?><br>
                    <?php endif; ?><br>
                <hr>
                <?php endforeach; ?>
            <?php endforeach; ?>

            </p>
        <?php endforeach; ?>

    </p>
</div>
