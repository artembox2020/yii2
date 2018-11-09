<?= Yii::t('frontend', 'Income') ?>:
<?= !is_null(['income']) ? $params['income'] : Yii::t('frontend', 'Undefined') ?><br>

<?= Yii::t('frontend', 'Created') ?>: <?= $params['created'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Deleted') ?>: <?= $params['deleted'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Active') ?>: <?= $params['active'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'All') ?>: <?= $params['all'] ?> <?= Yii::t('frontend', 'WM')?><br>

<?= Yii::t('frontend', 'Idle Hours') ?>: <?= $params['idleHours'] ?><br>

<?= Yii::t('frontend', 'Imei') ?>: <?= $params['imei'] ?><br>