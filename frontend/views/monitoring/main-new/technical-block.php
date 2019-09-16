<?php

use frontend\services\globals\EntityHelper;

/* @var $item array */
/* @var $rowspan int */
/* @var $model \frontend\models\ImeiDataSearch */

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
<?= 
    Yii::$app->view->render(
        '@frontend/views/monitoring/main-new/wm-mashines',
        [
            'item' => $item,
            'rowspan' => $rowspan,
            'model' => $model
        ]
    )
?>
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
            <?= !empty($item['terminal']['traffic']) ? $item['terminal']['traffic'].' Mb' : '' ?>
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