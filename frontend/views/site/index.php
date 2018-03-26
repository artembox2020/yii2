<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */


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
                <?= $address->address ?>
            <?php endforeach; ?> <b><?= Html::a(Yii::t('frontend', 'Add Imei'), ['/imei', 'id' => $model->id], ['class' => 'btn btn-success']) ?></b>
            </p>
        <?php endforeach; ?>
    </p>
</div>
