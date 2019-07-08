<?php

use frontend\services\globals\EntityHelper;

/* @var $rowspan int */
/* @var $searchModel frontend\models\ImeiDataSearch */
/* @var $item array */

?>
<td class="font12 counter bold-border" rowspan ="<?= $rowspan ?>">
    <?= $item['financial']['fireproof_residue'] ?>
    <?= EntityHelper::makePopupWindow(
            [],
            $searchModel->attributeLabels()['fireproof_residue'],
            'top: -5px',
            'height: 5px'
        );
    ?>                    
</td>
<td style="width: 20px;" class="font12 counter bold-border" rowspan ="<?= $rowspan ?>">
    <?= $item['financial']['money_in_banknotes'] ?>
    <?= EntityHelper::makePopupWindow(
            [],
            $searchModel->attributeLabels()['money_in_banknotes'],
            'top: -5px',
            'height: 5px'
        );
    ?>
</td>
<td valign="center" class="bg-lightgrey bold-border" rowspan ="<?= $rowspan ?>">
    <span class="font12 last">
        <?= $item['financial']['last_encashment'] ?>
    </span>
    <?= EntityHelper::makePopupWindow(
            [],
            $searchModel->attributeLabels()['date_sum_last_encashment'],
            'top: -5px',
            'height: 5px'
        );
    ?>
</td>
<td rowspan ="<?= $rowspan ?>" class ="bold-border">
    <span class="font12 last"><?= $item['financial']['pre_last_encashment'] ?></span>
    <?= EntityHelper::makePopupWindow(
            [],
            $searchModel->attributeLabels()['date_sum_pre_last_encashment'],
            'top: -5px',
            'height: 5px'
        );
    ?>
</td>
<td class="bg-lightgrey font12 counter bold-border" rowspan ="<?= $rowspan ?>">
    <?= $item['financial']['in_banknotes'] ?>
    <?= EntityHelper::makePopupWindow(
            [],
            $searchModel->attributeLabels()['in_banknotes'],
            'top: -5px',
            'height: 5px'
        );
    ?>
</td>