<?php if (Yii::$app->session->has('update-map-data-status')): ?>
    <div class="alert alert-info alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i><?= Yii::t('frontend','Info') ?></h4>
        <?= Yii::t('map', Yii::$app->session->get('update-map-data-status')) ?>
    </div>
    <?php Yii::$app->session->remove('update-map-data-status'); ?>
<?php endif; ?>