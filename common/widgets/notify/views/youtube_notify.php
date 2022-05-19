<?php

use \yii\base\DynamicModel;
use yii\widgets\Pjax;
use common\widgets\modal\ModalWidget;

?>

<?php Pjax::begin(['id' => 'add_notification', 'options' => ['class' => 'inline']]); ?>

<?php
$model = new DynamicModel(['channel_id', 'phone', 'add_notify']);
$model
    ->addRule(['channel_id','phone'], 'required')
    ->addRule(['add_notify'], 'safe')
    ->addRule(['phone'], 'match', ['pattern' => '^\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$'])
;
?>

<?php \common\widgets\notify\YoutubeNotifyWidget::notify($model); ?>

<?php if (Yii::$app->session->hasFlash('channel_notification_success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Done!</h4>
        <?= Yii::$app->session->getFlash('channel_notification_success') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('channel_notification_error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Error!</h4>
        <?= Yii::$app->session->getFlash('channel_notification_error') ?>
    </div>
<?php endif; ?>

<button class="btn btn-bitbucket panel-notification" data-toggle="modal" data-target="#addNotification">
    Add youtube notification
</button>

<?= ModalWidget::widget([
    'id' => 'addNotification',
    'title' => 'Add notification',
    'method' => 'POST',
    'model' => $model,
    'modelColumns' => ['channel_id', 'phone', 'add_notify'],
    'formClass' => 'youtube-notify-form',
    'isAjax' => true,
]) ?>

<?php Pjax::end(); ?>

<?php $this->registerCss(
    <<<CSS
    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .youtube-notify-form {
        display: none;
    }
    .field-dynamicmodel-add_notify {
        display: none;
    }
CSS
);
?>

<?php $this->registerJS(
    <<<JS
jQuery(function($) {
    $('body').on('click', '.panel-notification', function() {
        $('.youtube-notify-form').toggle();
    });
    $('body').on('submit', "#addNotification form", function () {
        $(this).closest('#addNotification').find('.close').click();
        $('.modal-backdrop').remove();
    });

    $('body').on('click', '[data-target="#addNotification"]', function() {
        $("#addNotification .youtube-notify-form").css('display', 'block');
    });
});
JS
);