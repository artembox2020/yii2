<?php

use frontend\services\globals\EntityHelper;

/* @var $data array */
/* @var $rowspan int */

?>
<td style="width: 40px;" rowspan="<?= $rowspan ?>"  class="font12 last">
    <input class="address-id" type="hidden" value="<?= $item['common']['id'] ?>" />
    <input class="address-serial-number" size="1" value="<?= $item['common']['serialNumber'] ?>" />
    <?=
        EntityHelper::makePopupWindow(
            [],
            Yii::t('frontend', 'Imei').': '.$item['common']['imei'],
            'top: -5px',
            'height: 5px'
        )
    ?>
</td>
<td rowspan="<?= $rowspan ?>" class="bg-lightgrey font12 last" >
    <a
        target = "_blank"
    <?php if (!$item['common']['is_deleted']): ?>
        href = "/net-manager/view-balance-holder?id=<?= $item['common']['bhId'] ?>"
    <?php endif; ?>
    >
        <?= $item['common']['bhName'].($item['common']['is_deleted'] ? "[".Yii::t('frontend', 'Deleted')."]" : "") ?>
    </a>
</td>
<td rowspan="<?= $rowspan ?>" class="font12 last">
    <a
        target = "_blank"
        href = "/address-balance-holder/view?id=<?= $item['common']['id'] ?>"
    >
        <?= $item['common']['address'].', '.$item['common']['floor'].
            EntityHelper::makePopupWindow(
                [],
                Yii::t('frontend', 'Address Name').': '.$item['common']['name'],
                'top: -5px',
                'height: 5px'
            )
        ?>
    </a>
    <input 
        type = "hidden" 
        class = "search-address-value"
        value = 
            "<?= 
                mb_strtolower($item['common']['address']).(
                    !empty($item['common']['floor']) ? 
                    ', '.mb_strtolower($item['common']['floor']) : ''
                );
            ?>"
    />
</td>