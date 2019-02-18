<?php

use frontend\models\ImeiData;

?>
<table class="table table-actions table-striped table-bordered table-bill-acceptance">
    <tbody>
        <tr>
            <td 
                class="cell-actions <?= $model->makeActiveActionClass($actions[ImeiData::TYPE_ACTION_STATE_REQUEST]) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_STATE_REQUEST] ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_STATE_REQUEST] ?>
            </td>
        </tr>
        <tr>
            <td 
                class="cell-actions <?= $model->makeActiveActionClass($actions[ImeiData::TYPE_ACTION_UPDATE_TERMINAL_SOFTWARE]) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_UPDATE_TERMINAL_SOFTWARE] ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_UPDATE_TERMINAL_SOFTWARE] ?>
            </td>
        </tr>
        <tr>
            <td
                class="cell-actions <?= $model->makeActiveActionClass($actions[ImeiData::TYPE_ACTION_CPU_RELOAD]) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_CPU_RELOAD] ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_CPU_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td
                class="cell-actions small <?= $model->makeActiveActionClass($actions[ImeiData::TYPE_ACTION_ZIGBEE_RELOAD]) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_ZIGBEE_RELOAD] ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_ZIGBEE_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td 
                class="cell-actions small <?= $model->makeActiveActionClass($actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD]) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD] ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td 
                class="cell-actions small <?= $model->makeActiveActionClass($actions[ImeiData::TYPE_ACTION_TIME_SET]) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_TIME_SET] ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_TIME_SET] ?>
            </td>
        </tr>
    </tbody>
</table>