<?php

use frontend\models\ImeiData;

?>
<table class="table table-actions table-striped table-bordered table-bill-acceptance">
    <tbody>
        <tr>
            <td class="cell-actions">
                <?= $actions[ImeiData::TYPE_ACTION_STATE_REQUEST] ?>
            </td>
        </tr>
        <tr>
            <td class="cell-actions">
                <?= $actions[ImeiData::TYPE_ACTION_UPDATE_TERMINAL_SOFTWARE] ?>
            </td>
        </tr>
        <tr>
            <td class="cell-actions">
                <?= $actions[ImeiData::TYPE_ACTION_CPU_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td class="cell-actions small">
                <?= $actions[ImeiData::TYPE_ACTION_ZIGBEE_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td class="cell-actions small">
                <?= $actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td class="cell-actions small">
                <?= $actions[ImeiData::TYPE_ACTION_TIME_SET] ?>
            </td>
        </tr>
    </tbody>
</table>