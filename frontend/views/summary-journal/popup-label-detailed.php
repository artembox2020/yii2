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

<br>
<?= Yii::t('summaryJournal', 'Idle Reasons Hours') ?><br><br>

<?= Yii::t('summaryJournal', 'Idle Work Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[0] ?><br>
<?= Yii::t('summaryJournal', 'Idle Connect Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[1] ?><br>
<?= Yii::t('summaryJournal', 'Idle Bill Acceptance Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[2] ?><br>
<?= Yii::t('summaryJournal', 'Idle Central Board Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[3] ?><br>
