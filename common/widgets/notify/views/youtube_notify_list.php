<?php

use common\models\YtChannelNotificationSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \yii\base\DynamicModel;
use common\widgets\modal\ModalWidget;

/* @var $searchModel
 * @var $dataProvider
 */

?>
<?php Pjax::begin(['id' => 'notifies', 'options' => ['class' => 'inline']]); ?>

<button class="btn btn-bitbucket show-panel-notification" data-toggle="modal" data-target="#showNotifications">
    Show notifications
</button>

<?php
$model = new DynamicModel(['phone', 'notify_list']);
$model
    ->addRule(['phone'], 'required')
    ->addRule(['notify_list'], 'safe')
    ->addRule(['phone'], 'match', ['pattern' => '^\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$'])
;
$model->load(Yii::$app->request->post());
$modelColumns = ['phone', 'notify_list'];
?>

<?= ModalWidget::widget([
    'id' => 'showNotifications',
    'method' => 'GET',
    'title' => 'Search notifications',
    'model' => $model,
    'modelColumns' => $modelColumns,
    'formClass' => 'search-notification-form',
    'isAjax' => true,
]) ?>

<?php if ( ! empty(Yii::$app->request->get('DynamicModel')) && array_key_exists('notify_list', Yii::$app->request->get('DynamicModel'))): ?>

<?php
    $searchModel = new YtChannelNotificationSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
?>
<h4>Notifications by phone <?= $model->phone ?></h4>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'channel_id',
        'channel.title',
        [
            'attribute' => 'created_at',
            'filter'    => false,
        ],
        /*[
            'label'=>'',
            'format'=>'raw',
            'value' => function($data) use ($model) {

                return $this->render('actions_list', ['data' => ['phraseId' => $data->id]]);
            }
        ],*/
    ],
]); ?>

<?php endif; ?>

<?php Pjax::end(); ?>

<?php $this->registerCss(
        <<<CSS
    .field-dynamicmodel-notify_list {
        display: none;
    }
CSS
); ?>

<?php $this->registerJS(
    <<<JS
jQuery(function($) {
    $('body').on('click', '.show-panel-notification', function() {
        $('.search-notification-form').toggle();
    });
    $('body').on('submit', "#showNotifications form", function () {
        $('.modal-backdrop').remove();
    });
    
    $('body').on('click', '[data-target="#showNotifications"]', function() {
        $("#showNotifications .search-notification-form").css('display', 'block');
    });
});    
JS
); ?>

