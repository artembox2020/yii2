<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */

$this->title = Yii::t('frontend', 'Imeis');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imei-index">

    <h2><?= Yii::t('frontend', 'Net Manager'); ?></h2>


    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <?php $form = ActiveForm::begin(['action' => '/balance-holder/create']) ?>

            <?php if (!empty($user = User::findOne(Yii::$app->user->id))) {
                if (!empty($user->company)) {
                    $company = $user->company;
                    $brand = $company->name;
                }
            }
            ?>
            <?= Html::hiddenInput('company_id', $company->id); ?>
            <?= Html::submitButton(Yii::t('frontend', 'Add Balance Holder'), ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-xs-12 col-sm-4">
            <?php $form = ActiveForm::begin([
                'action' => '/address-balance-holder/create'
            ]) ?>
            
            <?= Html::submitButton(Yii::t('frontend', 'Add Address'), ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-xs-12 col-sm-4">
            <?php $form = ActiveForm::begin([
                'action' => '/imei/create'
            ]) ?>

            <?= Html::submitButton(Yii::t('frontend', 'Add IMEI'), ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


    

    </p>

    <?php foreach ($balanceHolders as $item) : ?>
        <p>
        <?php foreach ($item->addressBalanceHolders as $address) : ?>
            <?= $item->name ?>
            (<?= $item->address ?>)
            <hr>
            <?php foreach ($address->imeis as $imei) : ?>
                IMEI: <?= $imei->imei ?>
                <?php if (!empty($imei->firmware_version)) : ?>
                    <?php echo 'Init: <b style="color: forestgreen">Ok</b>'; ?> <?= date('d.m.Y', $imei->updated_at); ?>
                    <?= $address->address ?>
                    Этаж: <?= $address->floor ?> | view | edit | delete |
                <?php elseif (empty($imei->firmware_version)) : ?>
                    <?php echo 'Init: <b style="color: brown">false</b>'; ?> <?= date('d.m.Y', $imei->updated_at); ?>.
                    <?= $address->address ?>
                    Этаж: <?= $address->floor ?> | view | edit | delete |
                <?php endif; ?><br>
            <?php endforeach; ?>
            <hr>
        <?php endforeach; ?>
        </p>
    <?php endforeach; ?>

</div>
<div class="country-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'init',
            'imei',
            'addressName',
            'type_packet',
            'imei_central_board',
            'firmware_version',
            'type_bill_acceptance',
            'serial_number_kp',
            'phone_module_number',
            'crash_event_sms',
            'critical_amount',
            'time_out',
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>