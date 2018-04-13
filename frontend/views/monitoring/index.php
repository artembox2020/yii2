<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
?>
<h1>monitoring</h1>

<p>
    <?php foreach ($balanceHolders as $item) : ?>
    <?= $item->name ?> (<?= $item->address ?>)<br>
        <?php foreach ($item->addressBalanceHolders as $address) : ?>
            <?php foreach ($address->imeis as $imei) : ?>
                [<?= $imei->imei ?>]
            <?php endforeach; ?>
            <?= $address->address ?>
            Этаж: <?= $address->floor ?>
            <?php foreach ($imei->getImeiData() as $value) : ?>
            <?php endforeach; ?>
            <br>
        <?php endforeach; ?>
        <br>
    <?php endforeach; ?>
<p>
</p>
