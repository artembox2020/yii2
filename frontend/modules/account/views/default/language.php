<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserProfile;
use bs\Flatpickr\FlatpickrWidget;
use vova07\fileapi\Widget as FileApi;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('frontend', 'Language');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Settings'), 'url' => ['settings']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-default-settings">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginForm(['/account/default/switch-language'], 'post') ?>
        <?= Html::hiddenInput('redirectTo', \yii\helpers\Url::to(Yii::$app->request->url)) ?>
        <?= Html::beginTag('select', ['name' => 'language', 'onchange' => 'this.form.submit();']) ?>
        <?= Html::renderSelectOptions(\Yii::$app->language, [
            'uk-UA' => 'Ukraine',
            'ru-RU' => 'Russian',
            'en-US' => 'English',
        ]) ?>
        <?= Html::endTag('select') ?>
        <?= Html::endForm() ?>
        <p><?= Yii::t('frontend', 'Current language') ?>: <?= Html::encode(\Yii::$app->language) ?> </p>

</div>
