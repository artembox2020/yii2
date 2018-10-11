<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $company frontend\models\Company */

//$this->title = $model->name;
//$this->params['breadcrumbs'][] = $this->title;

//$companyOptions = [];
//if(!empty($company)) {
//    $companyOptions = [
//        'value' => $company->id
//    ];
//}

?>
<div class="account-default-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($user, 'company')->dropDownList(
        \yii\helpers\ArrayHelper::map(frontend\models\Company::find()->all(), 'id', 'name')
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
