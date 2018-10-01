<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\BalanceHolderSummarySearch;

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
                for ($i = 1; $i <= $days; ++$i):
            ?>
                <td
                    data-timestamp-start = "<?= $data[$j][$i]['timestampStart'] ?>"
                    data-timestamp-end = "<?= $data[$j][$i]['timestampEnd'] ?>"
                    data-idle-hours = "<?= $data[$j]['incomes'][$i]['idleHours'] ?>"
                    class = "timestamp <?= $data[$j][$i]['class'] ?>"
                >
                <?php
                    echo isset($data[$j]['incomes'][$i]['income']) ? $data[$j]['incomes'][$i]['income'] : ' &nbsp;';
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