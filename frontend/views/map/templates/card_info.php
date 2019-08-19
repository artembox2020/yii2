<?php

use frontend\models\CustomerCards;

?>
<section class="cardinfo pl-4 pb-4 d-flex flex-column">
    <span class="text-left">
        <h3><?= Yii::t('map', 'Card Card') ?></h3>
    </span>
    <table>
        <tbody>
            <tr>
                <th><?= Yii::t('map', 'Card') ?></th>
                <td><?= $card->card_no ?></td>
                <td></td>
                <th>&nbsp;<?= Yii::t('map', 'Status') ?>&nbsp;</th>
                <td><?= CustomerCards::statuses($card->status) ?></td>
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Balance') ?></th>
                <td><?= $card->balance ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Discount') ?></th>
                <td><?= $card->discount ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th><?= Yii::t('map', 'User') ?></th>
                <td><?= $userProfile->findFlnameByUserId($card->user_id); ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Total Circulation') ?></th>
                <td><?= $transaction->findCirculationByCardNo($card->card_no) ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</section>