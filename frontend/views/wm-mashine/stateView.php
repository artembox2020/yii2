<?php

use frontend\models\WmMashine;

?>
<div class="mashine-state">
    <?= Yii::t('frontend', $mashine->getState()) ?>
</div>

<?php if (!empty($mashine->ping)): ?>
    <div class="mashine-state mashine-state-addons">
        <?= $mashine->getLastPing() ?>
        <?= Yii::t('frontend', 'On Display:').' '.$mashine->display ?>
    </div>
<?php endif; ?>