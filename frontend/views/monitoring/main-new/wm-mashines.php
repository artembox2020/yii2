<?php

use frontend\services\globals\EntityHelper;

/* @var $item array */
/* @var $rowspan int */
/* @var $model \frontend\models\ImeiDataSearch */

?>

<?php foreach ($item['devices'] as $mashine): ?>
    <td rowspan="<?= $rowspan ?>" class="bg-pm1grey bold-border" style = "max-width: 92px;">
        <input type="hidden" class="device-id" value="<?= $mashine['id'] ?>">
        <a class = "wm-mashine-link" target= "_blank" href= "/net-manager/wm-machine-view?id=<?= $mashine['id'] ?>">
            <span class="font12" style="white-space: nowrap;">
                <?= Yii::t('frontend', $mashine['type']).$mashine['number_device'] ?>
                <span class="<?= $mashine['indicator'] ?> label d-inline-block font12" >
                    <?= !empty(trim($mashine['display'])) ? '&nbsp;'.$mashine['display'].'&nbsp;' :  '' ?>
                </span>
            </span>
            <span class="font10 height40 connect-status <?= $mashine['no_connection'] ?>"><?= $mashine['current_status'] ?></span><br>
        </a>
        <span class="font12 inline-adjustment <?= $mashine['indicator'] ?>">
            <?= $mashine['last_ping'] ?>
        </span>
        <br>
        <span class="font12">
            <span>
                <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/Fill_1.png" class="pr-2" alt="гривня"><?= $mashine['bill_cash'] ?>
            </span>
            <?= EntityHelper::makePopupWindow(
                    [],
                    Yii::t('frontend', 'On the bill of WM'),
                    'top: -5px',
                    'height: 5px'
                )
            ?>
            <span class="font12">
                <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/signal.png" class="pr-2" alt="signal"><?= $mashine['level_signal'] ?>
            </span>
            <?= EntityHelper::makePopupWindow(
                    [],
                    $model->attributeLabels()['level_signal'],
                    'top: -5px',
                    'height: 5px'
                )
            ?>
        </span>    
    </td>
<?php endforeach; ?>