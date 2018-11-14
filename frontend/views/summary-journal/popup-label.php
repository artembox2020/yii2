<?= Yii::t('frontend', 'Income') ?>:
<?= !is_null(['income']) ? $params['income'] : Yii::t('frontend', 'Undefined') ?><br>

<?= Yii::t('frontend', 'Created') ?>: <?= $params['created'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Deleted') ?>: <?= $params['deleted'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Active') ?>: <?= $params['active'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'All') ?>: <?= $params['all'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Idle Hours') ?>: <?= $params['idleHours'] ?><br>

<?= Yii::t('frontend', 'Imei') ?>: <?= $params['imei'] ?><br>

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

<?= Yii::t('frontend', 'Cancel Statistics') ?>

<?= Yii::$app->view->render('/summary-journal/data/pjax_form', [
    'params' => $params,
    'start' => $start,
    'end' => $end + 1,
    'random' => $random
]) ?>