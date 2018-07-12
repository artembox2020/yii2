<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Imei */
/* @var $model frontend\models\BalanceHolder */
/* @var $model frontend\models\Company */
/* @var $address */

$this->title = Yii::t('frontend', 'Create Imei');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Imeis'), 'url' => ['/net-manager/washpay']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imei-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
    ]) ?>

</div>
