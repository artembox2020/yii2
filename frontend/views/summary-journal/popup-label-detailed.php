<?= Yii::t('frontend', 'Income') ?>:
<?= !is_null(['income']) ? $params['income'] : Yii::t('frontend', 'Undefined') ?><br>

<?= Yii::t('frontend', 'Is Deleted') ?>: 
<?= !empty($params['deleted']) ? Yii::t('frontend', 'Yes') : Yii::t('frontend', 'No') ?><br>

<?= Yii::t('frontend', 'Is Created') ?>:
<?= !empty($params['created']) ? Yii::t('frontend', 'Yes') : Yii::t('frontend', 'No') ?><br>

<?= Yii::t('frontend', 'Idle Hours') ?>: <?= $params['idleHours'] ?><br>

<?= Yii::t('frontend', 'All Hours') ?>: <?= $params['allHours'] ?><br>

<?php if (!empty($params['encashment_date'])): ?>

    <?= Yii::t('frontend', 'Encashment Date') ?>:
    <?= \Yii::$app->formatter->asDate($params['encashment_date'], 'short') ?><br>
    <?= Yii::t('frontend', 'Encashment Sum') ?>: <?= $params['encashment_sum'] ?><br>
<?php endif; ?>

<?= Yii::t('frontend', 'Imei') ?>: <?= $params['imei'] ?><br>

<?= Yii::t('frontend', 'Events') ?>: <?= $searchModel->getEventsAsString($params) ?><br>

<?php
if (!empty($params['idleHours'])) { 

    echo Yii::$app->view->render('/summary-journal/idle-hours-reasons', [
        'idleHoursReasons' => $params['idleHoursReasons']
    ]);
}
?>