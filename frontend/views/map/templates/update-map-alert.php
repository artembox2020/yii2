<?php if (Yii::$app->session->hasFlash('updateMapData')): ?>
    <div class="alert alert-info alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i><?= Yii::t('frontend','Info') ?></h4>
        <?= Yii::t('map', Yii::$app->session->getFlash('updateMapData')) ?>
    </div>
<?php endif; ?>