<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserProfile;
use bs\Flatpickr\FlatpickrWidget;
use vova07\fileapi\Widget as FileApi;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('frontend', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-default-settings">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
                    <?= Html::a(Yii::t('frontend', 'Change password'), ['password'], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('frontend', 'User'), ['user'], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('frontend', 'Language'), ['language'], ['class' => 'btn btn-primary']) ?>
    </div>

</div>
