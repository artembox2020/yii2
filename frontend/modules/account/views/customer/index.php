<?php

use yii\helpers\Html;
use frontend\components\responsive\DetailView;
use common\models\UserProfile;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;

?>
<div class="account-default-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($profile->avatar_path) : ?>
            <img src="<?= Yii::getAlias('@storageUrl/avatars/' . $profile->avatar_path) ?>" class="img-thumbnail" alt>
        <?php else: ?>
            <img src="<?= Yii::$app->homeUrl . '/static/img/default.png' ?>" class="img-thumbnail" alt>
        <?php endif ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => Yii::t('frontend', 'Firstname'),
                'value' => $profile->firstname,
                'visible' => $profile->firstname !== null,
            ],
            [
                'attribute' => Yii::t('frontend', 'Lastname'),
                'value' => $profile->lastname,
                'visible' => $profile->lastname !== null,
            ],
            [
                'attribute' => Yii::t('frontend', 'Birthday'),
                'format' => 'date',
                'value' => $profile->birthday,
                'visible' => $profile->birthday !== null,
            ],
            [
                'attribute' => Yii::t('frontend', 'Gender'),
                'value' => $profile->gender == UserProfile::GENDER_MALE ? Yii::t('frontend', 'Male') : Yii::t('frontend', 'Female'),
                'visible' => $profile->gender !== null,
            ],
            [
                'attribute' => Yii::t('frontend', 'Other'),
                'value' => $profile->other,
                'visible' => $profile->other !== null,
            ],
            'created_at:datetime',
            'action_at:datetime',
        ],
    ]) ?>
</div>