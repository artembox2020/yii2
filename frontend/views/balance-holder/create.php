<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\BalanceHolder */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $company frontend\models\Company */

$this->title = Yii::t('frontend', 'Create Balance Holder');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Balance Holders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="balance-holder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'balanceHolders' => $balanceHolders,
        'company' => $company
    ]) ?>

</div>
