<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

//$this->title = $model->name;
//$this->params['breadcrumbs'][] = $this->title;

$companyOptions = [];
if(!empty($model)) {
    $companyOptions = [
        'value' => $model->company_id
    ];
}

?>
<div class="account-default-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
            'action' => ['default/tt'],
        ]
    ); ?>

    <?= $form->field($model, 'Company')->dropDownList(
        \yii\helpers\ArrayHelper::map(frontend\models\Company::find()->all(), 'id', 'name'),
        $companyOptions
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
