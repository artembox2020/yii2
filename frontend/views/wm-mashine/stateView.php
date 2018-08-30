<?php

use frontend\models\WmMashine;

?>
<div class="mashine-state">
    <?= Yii::t('frontend', $mashine->getState()) ?>
</div>

<?php if(!empty($mashine->ping)): ?>
<div class="row">
    <div class="col-md-6 mashine-state-addons">
        <div><?= $mashine->display ?></div>
        <?= date(WmMashine::DATE_TIME_FORMAT, $mashine->ping) ?>
    </div>
</div>
<?php endif; ?>