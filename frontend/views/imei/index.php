<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */

$this->title = Yii::t('frontend', 'Imeis');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imei-index">

    <h2><?= Yii::t('frontend', 'Net Manager');  ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php $form = ActiveForm::begin([
                            'action' => '/balance-holder/create'
                        ]) ?>

<?php if (!empty($user = User::findOne(Yii::$app->user->id))) {
        if (!empty($user->company)) {
            $company = $user->company;
            $brand = $company->name;
        }
    }
?>
<?=  Html::hiddenInput('company_id', $company->id); ?>
<?= Html::submitButton(Yii::t('frontend', 'Add Balance Holder'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>
<?php $form = ActiveForm::begin([
                            'action' => '/address-balance-holder/create'
                        ]) ?>


<?= Html::submitButton(Yii::t('frontend', 'Add Address'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>
<?php $form = ActiveForm::begin([
                            'action' => '/imei/create'
                        ]) ?>


<?= Html::submitButton(Yii::t('frontend', 'Add IMEI'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>
    </p>
<!--
    // GridView::widget([
    //     'dataProvider' => $dataProvider,
    //     'filterModel' => $searchModel,
    //     'columns' => [
    //         ['class' => 'yii\grid\SerialColumn'],

    //         'id',
    //         'imei',
    //         [
    //             'attribute' => 'address_id',
    //             'format' => 'html',
    //             'value' => function ($data) {
    //                 return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['address_id'],['max-width' => '80px']));
    //             },
    //         ],
    //         'type_packet',
    //         'imei_central_board',
    //         'firmware_version',
    //         'type_bill_acceptance',
    //         'serial_number_kp',
    //         'phone_module_number',
    //         'crash_event_sms',
    //         'critical_amount',
    //         'time_out:datetime',
    //         'created_at',
    //         'updated_at',
    //         'is_deleted',
    //         'deleted_at',

    //         ['class' => 'yii\grid\ActionColumn'],
    //     ],
    // ]); 
-->
</div>