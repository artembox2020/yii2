<?php

use yii\helpers\Html;
use frontend\models\BalanceHolderSummarySearch;
use frontend\services\globals\EntityHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\BalanceHoderSummarySearch */
/* @var $data array */

?>
<div>
    <table class="table table-bordered table-income">
        <tbody>
        <?php
            for ($j = 0; $j < count($data) - 2; ++$j):
        ?>
            <tr>
            <?php
                for ($i = 1; $i <= count($data[$j]['incomes']); ++$i):
            ?>
                <td
                    data-timestamp-start = "<?= $data[$j][$i]['timestampStart'] ?>"
                    data-timestamp-end = "<?= $data[$j][$i]['timestampEnd'] ?>"
                    data-idle-hours = "<?= $data[$j]['incomes'][$i]['idleHours'] ?>"
                    data-is-deleted = "<?= $data[$j]['incomes'][$i]['isDeleted'] ?>"
                    data-is-created = "<?= $data[$j]['incomes'][$i]['isCreated'] ?>"
                    class = " timestamp <?= $data[$j][$i]['class'] ?>"
                >
                <?php
                    echo (
                        isset($data[$j]['incomes'][$i]['income']) ? $data[$j]['incomes'][$i]['income'] : ' &nbsp;'
                    ).
                    EntityHelper::makePopupWindow(
                        [],
                        $summaryJournalController->renderPopupLabelDetailed($data[$j]['incomes'][$i]),
                        'color: black; text-align: left;',
                        'height: 10px; position: absolute;'
                    );
                ?>
                </td>
            <?php
                endfor; 
            ?>
            </tr>
        <?php
            endfor;
        ?>
            <tr>
                <?php for ($i = 1, $j = 0; $i <= $days; ++$i) { ?>
                    <td><?= isset($data['summaryTotal'][$i]) ? $data['summaryTotal'][$i] : '&nbsp;&nbsp;' ?></td>
                <?php } ?>
            </tr>
            <tr>
                <?php for ($i = 1, $j = 0; $i <= $days; ++$i) { ?>
                    <td>
                    <?= !empty($data['countTotal']) ?
                        $searchModel->parseFloat($data['summaryTotal'][$i] / $data['countTotal'], 2) : 0 
                    ?>
                    </td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
</div>