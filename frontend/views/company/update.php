<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Company */

$this->title = Yii::t('frontend', 'Update Company: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br>
<div class="company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
