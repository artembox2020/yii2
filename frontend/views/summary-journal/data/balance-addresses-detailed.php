<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\BalanceHolder;
use frontend\models\BalanceHolderSummarySearch;
use frontend\services\globals\QueryOptimizer;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\BalanceHolderSummarySearch */
?>
<div>
    <table class="table table-bordered table-container">
        <tbody>
            <?php 
                foreach (BalanceHolder::getItemsByDataProvider($dataProvider) as $balanceHolder):
            ?>
            <tr data-key="<?= $balanceHolder->id ?>">
                <td class="mashine-number-device cell-device">
                    <?= $balanceHolder->name ?>
                </td>
                <td class="is_deleted hidden">
                    <?= $balanceHolder->is_deleted ?>
                </td>
                <td class="deleted_at hidden">
                    <?= $balanceHolder->deleted_at ?>
                </td>
                <td class="date-inserted hidden">
                    <?= $balanceHolder->created_at ?>
                </td>
                <td class="address-container balance-address-container">
                    <table
                        data-count = "<?= $balanceHolder->getAddressBalanceHoldersCountByTimestamp($timestampStart, $timestampEnd) ?>"
                        class = "table table-bordered table-address-container"
                    >
                        <tbody>
                        <?php
                            foreach ($balanceHolder->getAddressBalanceHoldersByTimestamp($timestampStart, $timestampEnd) as $address):
                                $mashinesQuery = $searchModel->getAllMashinesQueryByYearMonth($year, $month, $address);
                                $mashinesQueryCount = QueryOptimizer::getItemsCountByQuery($mashinesQuery);
                        ?>
                            <tr>
                                <td class = "mashine-number-device address">
                                    <?= $address->address ?>,
                                    <?= $address->floor ? $address->floor : ' &nbsp;' ?>
                                </td>
                                <td class = "mashine-numbers-cell"
                                    data-mashines-number = "<?= $mashinesQueryCount ?>"
                                >
                                    <table class="table table-bordered mashine-numbers">
                                    <?php if ($mashinesQueryCount == 0): ?>
                                        <tr>
                                            <td>0</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach(QueryOptimizer::getItemsByQuery($mashinesQuery) as $mashine): ?>
                                        <tr>
                                            <td class="mashine-number-device address-id-<?= $mashine->id ?>"> &nbsp;<?= $mashine->number_device ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </table>
                                </td>
                                <td class="mashine-count hidden">
                                    <?= $mashinesQueryCount ?>
                                </td>
                                <td class="number-of-citizens hidden">
                                    <?= $address->number_of_citizens ?>
                                </td>
                                <td class="date-inserted hidden">
                                    <?= $address->created_at ?>
                                </td>
                                <td class="is_deleted hidden">
                                    <?= $address->is_deleted ?>
                                </td>
                                <td class="deleted_at hidden">
                                    <?= $address->deleted_at ?>
                                </td>
                            </tr>
                        <?php
                            endforeach; 
                        ?>                        
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php
                endforeach;
            ?>
        </tbody>
    </table>
</div>