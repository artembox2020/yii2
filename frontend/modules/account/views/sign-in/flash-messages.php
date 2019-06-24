<?php if (Yii::$app->session->hasFlash('password-reset-success')): ?>
<div class="alert alert-success">
  <?= Yii::$app->session->getFlash('password-reset-success') ?>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('password-reset-error')): ?>
<div class="alert alert-warning">
  <?= Yii::$app->session->getFlash('password-reset-error') ?>
</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('info')): ?>
<div class="alert alert-info">
  <?= Yii::$app->session->getFlash('info') ?>
</div>
<?php endif; ?>