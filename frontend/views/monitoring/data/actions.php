<?php

use frontend\models\ImeiData;

?>
<table class="table table-actions table-striped table-bordered table-bill-acceptance">
    <tbody>
        <tr>
            <td 
                class="cell-actions <?= $model->makeActiveActionClass(ImeiData::TYPE_ACTION_CPU_RELOAD) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_CPU_RELOAD] ?>"
                data-action-id = "<?= ImeiData::TYPE_ACTION_CPU_RELOAD ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_CPU_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td 
                class="cell-actions <?= $model->makeActiveActionClass(ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD] ?>"
                data-action-id = "<?= ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td
                class="cell-actions small <?= $model->makeActiveActionClass(ImeiData::TYPE_ACTION_ZIGBEE_RELOAD) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_ZIGBEE_RELOAD] ?>"
                data-action-id = "<?= ImeiData::TYPE_ACTION_ZIGBEE_RELOAD ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_ZIGBEE_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td 
                class="cell-actions small <?= $model->makeActiveActionClass(ImeiData::TYPE_ACTION_MODEM_RELOAD) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_MODEM_RELOAD] ?>"
                data-action-id = "<?= ImeiData::TYPE_ACTION_MODEM_RELOAD ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_MODEM_RELOAD] ?>
            </td>
        </tr>
        <tr>
            <td
                class="cell-actions <?= $model->makeActiveActionClass(ImeiData::TYPE_ACTION_LOGDISK_FORMAT) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_LOGDISK_FORMAT] ?>"
                data-action-id = "<?= ImeiData::TYPE_ACTION_LOGDISK_FORMAT ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_LOGDISK_FORMAT] ?>
            </td>
        </tr>
        <tr>
            <td 
                class="cell-actions small <?= $model->makeActiveActionClass(ImeiData::TYPE_ACTION_TIME_SET) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_TIME_SET] ?>"
                data-action-id = "<?= ImeiData::TYPE_ACTION_TIME_SET ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_TIME_SET] ?>
            </td>
        </tr>
        <tr>
            <td
                class="cell-actions <?= $model->makeActiveActionClass(ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_BLOCK) ?>"
                data-action = "<?= $actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_BLOCK] ?>"
                data-action-id = "<?= ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_BLOCK ?>"
                data-imei_id = "<?= $model['imei_id'] ?>"
                data-imei = "<?= $model['imei'] ?>"
            >
                <?= $actions[ImeiData::TYPE_ACTION_BILL_ACCEPTANCE_BLOCK] ?>
            </td>
        </tr>
    </tbody>
</table>