<?= Yii::t('frontend', 'Income') ?>:
<?= !is_null(['income']) ? $params['income'] : Yii::t('frontend', 'Undefined') ?><br>

<?= Yii::t('frontend', 'Created') ?>: <?= $params['created'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Deleted') ?>: <?= $params['deleted'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Active') ?>: <?= $params['active'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'All') ?>: <?= $params['all'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Idle Hours') ?>: <?= $params['idleHours'] ?><br>

<?= Yii::t('frontend', 'All Hours') ?>: <?= $params['allHours'] ?><br>

<?php if (!empty($params['encashment_date'])): ?>

    <?= Yii::t('frontend', 'Encashment Date') ?>:
    <?= \Yii::$app->formatter->asDate($params['encashment_date'], 'short') ?><br>
    <?= Yii::t('frontend', 'Encashment Sum') ?>: <?= $params['encashment_sum'] ?><br>
<?php endif; ?>

<?= Yii::t('frontend', 'Imei') ?>: <?= $params['imei'] ?><br>

<?= Yii::t('frontend', 'Events') ?>: <?= $searchModel->getEventsAsString($params) ?><br>

<input 
    type="checkbox"
    name="cancel-income"
    data-imei_id = "<?= $params['imei_id'] ?>"
    data-address_id = "<?= $params['address_id'] ?>"
    data-start = "<?= $start ?>"
    data-end = "<?= $end + 1 ?>"
    data-random = "<?= $random ?>"
    data-cancelled = "<?= !empty($params['is_cancelled']) ? 1 : 0 ?>"
/>

<span class="cancel-income"><?= Yii::t('frontend', 'Cancel Statistics') ?></span><br/>

<br>
<?= Yii::t('summaryJournal', 'Idle Reasons Hours') ?><br><br>

<?= Yii::t('summaryJournal', 'Idle Work Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[0] ?><br>
<?= Yii::t('summaryJournal', 'Idle Connect Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[1] ?><br>
<?= Yii::t('summaryJournal', 'Idle Bill Acceptance Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[2] ?><br>
<?= Yii::t('summaryJournal', 'Idle Central Board Hours') ?>: <?= explode("**", $params['idleHoursReasons'])[3] ?><br>

<?= Yii::$app->view->render('/summary-journal/data/pjax_form', [
    'params' => $params,
    'start' => $start,
    'end' => $end + 1,
    'random' => $random
]) ?>