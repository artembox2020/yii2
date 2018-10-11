<?php
error_reporting( E_ERROR );

use yii\jui\DatePicker;
use yii\helpers\Html;
use frontend\models\Devices;
use frontend\models\BalanceHolderSummarySearch;
use frontend\models\BalanceHolderSummaryDetailedSearch;
use frontend\models\Zlog;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $oneDataProvider yii\data\ActiveDataProvider */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel  frontend\models\BalanceHolderSummarySearch*/
/* @var $summaryJournalController frontend\controllers\SummaryJournalController */
?>
<h1><?= Yii::t('frontend', 'Summary Journal(Detailed)') ?></h1>
<?= $summaryJournalController->renderForm($params, $months, $years, $typesOfDisplay) ?>
<div class="summary-journal">
    <?= GridView::widget([
        'dataProvider' => $oneDataProvider,
        'filterModel' => false,
        'summary' => '',
        'tableOptions' => [
            'class' => 'table table-bordered main-table'
        ],
        'rowOptions' => [
            'class' => 'main-table-row'
        ],
        'columns' => [
            [
                'label' => Yii::t('frontend', 'Number'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $dataProvider)
                {
                    return $summaryJournalController->renderSerialColumn(
                        BalanceHolderSummaryDetailedSearch::getTotalMashinesCount() + 
                        BalanceHolderSummaryDetailedSearch::getTotalAddressesCount() + 2
                    );
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'red-color green-color']
            ],
            [
                'label' => $monthName,
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider, $params)
                {
                    return $summaryJournalController->renderBalanceAddressesDetailed($searchModel, $dataProvider, $params);
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'common all green-color red-color']
            ],
            [
                'header' => $summaryJournalController->renderMonthDays($params),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider, $params)
                {
                    return $summaryJournalController->renderIncomesByMashines($searchModel, $dataProvider, $params);
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'month-days all']
            ],
            [
                'label' => Yii::t('frontend', 'Incomes'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider, $params)
                {
                    return $summaryJournalController->renderIncomesSummaryByAddresses();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'incomes all']
            ],
            [
                'label' => Yii::t('frontend', 'Incomes By Day'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderAverageSummaryByAddresses();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'incomes all']
            ],/*
            [
                'label' => Yii::t('frontend', 'Incomes By Mashine'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderAverageMashineSummaryByAddresses();
                },
                'contentOptions' => ['class' => 'common all by-mashine'],
                'headerOptions' => ['class' => 'incomes all']
            ],*/
            [
                'label' => Yii::t('frontend', 'Incomes By Citizens'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderAverageCitizensSummaryByAddresses();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'incomes all']
            ],
            [
                'label' => Yii::t('frontend', 'Consolidated Summary'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderConsolidatedSummaryByAddresses();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'incomes all']
            ],
            [
                'label' => Yii::t('frontend', 'Expectation'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderExpectation();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'expectation all']
            ],
            [
                'label' => Yii::t('frontend', 'Expectation By Balance Holders'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderExpectationByBalanceHoders();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'expectation all']
            ],
            [
                'label' => Yii::t('frontend', 'Idle Days'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderIdleDays();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'idle-days all']
            ],
            [
                'label' => Yii::t('frontend', 'Idle Damages'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderIdleDamages();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'idle-damages all']
            ],
            [
                'label' => Yii::t('frontend', 'Month Conclusion'),
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($summaryJournalController, $searchModel, $dataProvider)
                {
                    return $summaryJournalController->renderSummaryConclusion();
                },
                'contentOptions' => ['class' => 'common all'],
                'headerOptions' => ['class' => 'incomes all']
            ],
        ],
    ]); ?>
</div>
<?php echo $script; ?>
<?php
    echo $submitFormOnInputEvents;
    echo Html::endForm();
?>
