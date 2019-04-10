<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
/* @var $balanceHoldersData  */

$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            /*[
                'attribute' => 'img',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['img'],['max-width' => '80px']));
                },
            ],*/
            'phone',
            'website',
        ],
    ]) ?>

    <b><?= Yii::t('frontend', 'Employees company') ?></b>

    <div class="employees-list">
        <?= yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => Yii::t('frontend', 'Employee'),
                    'value' => function($data) {
                        return $data->userProfile->firstname." ".$data->userProfile->lastname;
                    },
                ],

                [
                    'attribute' => 'position',
                    'label' => Yii::t('common', 'Position'),
                    'value' => function($data) {
                        return $data->userProfile->position;
                    },
                ],

                [
                    'label' => Yii::t('frontend', 'Access Level'),
                    'value' => function($data) {
                        return  $data->getUserRoleName($data->id);
                    },
                ],
            ],
        ]); ?>
    </div>

    <b><?= Yii::t('frontend', 'Balance Holders'); ?></b>
    <br>

    <div class="balance-holders-list">
        <?= Yii::$app->view->render('balance_holders', ['balanceHolders' => $balanceHolders, 'balanceHoldersData' => $balanceHoldersData]) ?>
    </div>
</div>
