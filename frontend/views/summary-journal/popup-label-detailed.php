
<?php if (!is_null($params['income'])): ?>
    <?php echo Yii::t('frontend', 'Income').': '.$params['income'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($params['created'])): ?>
    <?php echo Yii::t('frontend', 'Created').': '.$params['created'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($params['deleted'])): ?>
    <?php echo Yii::t('frontend', 'Deleted').': '.$params['deleted'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($params['active'])): ?>
    <?php echo Yii::t('frontend', 'Active').': '.$params['active'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($params['all'])): ?>
    <?php echo Yii::t('frontend', 'All').': '.$params['all'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($params['idleHours'])): ?>
    <?php echo Yii::t('frontend', 'Idle Hours').': '.$params['idleHours'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($params['allHours'])): ?>
    <?php echo Yii::t('frontend', 'All Hours').': '.$params['allHours'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($params['encashment_date'])): ?>
    <?= Yii::t('frontend', 'Encashment Date') ?>:
    <?= \Yii::$app->formatter->asDate($params['encashment_date'], 'short') ?><br>
    <?= Yii::t('frontend', 'Encashment Sum') ?>: <?= $params['encashment_sum'] ?><br>
<?php endif; ?>

<?php if (!empty($params['imei'])): ?>
    <?php echo Yii::t('frontend', 'Imei').': '.$params['imei'].'<br>'; ?>
<?php endif; ?>

<?php if (!empty($eventsString=$searchModel->getEventsAsString($params, $addressId, $start, $end))): ?>
    <?php echo Yii::t('frontend', 'Events').': '.$eventsString.'<br>'; ?>
<?php endif; ?>

<?php
if (!empty($params['idleHours'])) {

    echo Yii::$app->view->render('/summary-journal/idle-hours-reasons', [
        'idleHoursReasons' => $params['idleHoursReasons']
    ]);
}
?>