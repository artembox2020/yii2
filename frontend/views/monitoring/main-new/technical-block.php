<?php

use frontend\services\globals\EntityHelper;

/* @var $item array */

?>
<td style="width: 15px;" class="font12 pt-3">
    <?= $item['software']['firmware_version_cpu'] ?>
</td>
<td style="width: 15px;" class="width-restrict">
    <span class="<?= $item['terminal']['last_ping_class'] ?> font12">
        <?= $item['terminal']['last_ping_value'] ?>
    </span>
</td>
<td class="font12 <?= $item['terminal']['last_ping_class'].' '.$item['terminal']['error']  ?>" style="width: 15px;">
    <i class="fas fa-circle color-green"></i> <?= Yii::t('frontend', 'Software Versions') ?>
</td>
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
            <span class="font10 height40"><?= $mashine['current_status'] ?></span><br>
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
    </td>
<?php endforeach; ?>
</tr>
<tr>
    <td class="table-active font12">
        <?= $item['software']['firmware_6lowpan']. ' '.$item['software']['number_channel'].'Ch' ?>
    </td>
    <td class="table-active font12 width-restrict">
        <div style="float: left;">
            <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/signal.png" class="pr-2" alt="signal">
            <?= $item['terminal']['level_signal'] ?>
            <?= 
            EntityHelper::makePopupWindow(
                [],
                $model->attributeLabels()['level_signal'],
                'top: -5px',
                'height: 5px'
            )
            ?>
        </div>

        <div style="float: right;">
            <?= $item['terminal']['money_in_banknotes'] ?>
            <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/Fill_1.png" alt="гривня">
            <?= 
                EntityHelper::makePopupWindow(
                    [],
                    $model->attributeLabels()['on_modem_account'],
                    'top: -5px; display: block',
                    'height: 5px'
                )
            ?>
        </div>
    </td>
    <td class="table-active font12 <?= $item['terminal']['last_ping_class'] ?>">
        <div style = "white-space: nowrap; max-width:60px;">
        <img 
            src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/<?=$item['terminal']['fullnessIndicator'] ?>"
            class="x2 fullness-icon" alt="red icon"
        >
        <?= $item['terminal']['fullness'] ?>%
        <?= 
            EntityHelper::makePopupWindow(
                [],
                $model->attributeLabels()['bill_acceptance_fullness'],
                'top: -5px',
                'height: 5px'
            )
        ?>
        </div>
    </td>
</tr>
<tr>
    <td class="font12">
        <?= $item['software']['firmware_version'] ?>
    </td>
    <td class="font12 width-restrict">
        <img
            src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/phone.svg"
            class="pr-2" alt="phone"
        >
        <?= $item['terminal']['phone_number'] ?>
    </td>
    <td class="font12">
        <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/money.svg" alt="money">
        <?= $item['terminal']['in_banknotes'] ?>
        <?= 
            EntityHelper::makePopupWindow(
                [],
                $model->attributeLabels()['in_banknotes'],
                'top: -5px',
                'height: 5px'
            )
        ?>
    </td>
</tr>
<tr>
    <td class="table-active bold-border">&nbsp;</td>
    <td class="table-active font12 bold-border width-restrict">
        <?= $item['terminal']['imei'] ?>
    </td>
    <td class="table-active font12 bold-border">
        <?= explode(" ", $item['terminal']['last_ping_value'])[0] ?>
    </td>
</tr>