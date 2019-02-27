<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
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
                $balanceHolders = QueryOptimizer::getItemsByQuery($dataProvider->query);
                foreach ($balanceHolders as $balanceHolder):
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
                        data-count = "<?= QueryOptimizer::getItemsCountByQuery($balanceHolder->getAddressBalanceHoldersQueryByTimestamp($timestampStart, $timestampEnd)) ?>"
                        class = "table table-bordered table-address-container"
                    >
                        <tbody>
                        <?php
                            $addresses = QueryOptimizer::getItemsByQuery($balanceHolder->getAddressBalanceHoldersQueryByTimestamp($timestampStart, $timestampEnd));
                            foreach ($addresses as $address):
                        ?>
                            <tr>
                                <td class="mashine-number-device address">
                                    <?= $address->address ?>,
                                    <?= $address->floor ? $address->floor : ' &nbsp;' ?>
                                </td>
                                <td class="mashine-count address-id-<?= $address->id ?>">
                                    <?= $addressImeiData->getWmMashinesCountByYearMonth($year, $month, $address) ?>
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