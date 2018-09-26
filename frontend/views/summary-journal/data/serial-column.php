<?php

/* @var $recordQuantity integer */
?>
<div>
    <table class="table table-serial-column">
        <tbody>
            <?php for ($i = 1; $i <= $recordQuantity; ++$i): ?>
            <tr data-key="<?= $i ?>">
                <td class="cell-device"><?= $i ?></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>