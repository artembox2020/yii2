<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\BalanceHolderSummarySearch;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\BalanceHolderSummarySearch */
?>
<div>
    <table class="table table-bordered table-container">
        <tbody>
            <?php 
                foreach ($dataProvider->query->all() as $balanceHolder):
            ?>
            <tr data-key="<?= $balanceHolder->id ?>">
                <td class="cell-device">
                    <?= $balanceHolder->name ?>
                </td>
                <td class="address-container">
                    <table
                        data-count = "<?= $balanceHolder->getAddressBalanceHoldersQueryByTimestamp($timestampStart, $timestampEnd)->count() ?>"
                        class = "table table-bordered table-address-container"
                    >
                        <tbody>
                        <?php
                            foreach ($balanceHolder->getAddressBalanceHoldersQueryByTimestamp($timestampStart, $timestampEnd)->all() as $address):
                        ?>
                            <tr>
                                <td class="address">
                                    <?= $address->address ?>
                                </td>
                                <td class="mashine-count">
                                    <?= $searchModel->getAllMashinesQueryByYearMonth($year, $month, $address)->count() ?>
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