<?= Yii::t('frontend', 'Income') ?>:
<?= !is_null(['income']) ? $params['income'] : Yii::t('frontend', 'Undefined') ?><br>

<?= Yii::t('frontend', 'Is Deleted') ?>: 
<?= !empty($params['isDeleted']) ? Yii::t('frontend', 'Yes') : Yii::t('frontend', 'No') ?><br>

<?= Yii::t('frontend', 'Is Created') ?>:
<?= !empty($params['isCreated']) ? Yii::t('frontend', 'Yes') : Yii::t('frontend', 'No') ?><br>

<?= Yii::t('frontend', 'Idle Hours') ?>: <?= $params['idleHours'] ?><br>
<?= Yii::t('frontend', 'Imei') ?>: <?= $params['imei'] ?><br>