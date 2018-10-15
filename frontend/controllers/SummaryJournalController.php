<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\BalanceHolderSummarySearch;
use frontend\models\BalanceHolderSummaryDetailedSearch;
use frontend\models\WmMashine;
use Yii;
use yii\filters\AccessControl;
use frontend\services\globals\EntityHelper;

class SummaryJournalController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Main summary journal action
     * 
     * @return string
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        if (
            isset(Yii::$app->request->get()['type']) 
            && Yii::$app->request->get()['type'] == BalanceHolderSummarySearch::TYPE_DETAILED
        ) {
            $redirectUrl = array_merge(['index-detailed'], Yii::$app->request->queryParams);

            return $this->redirect($redirectUrl);
        }
        
        $searchModel = new BalanceHolderSummarySearch();

        $dataProvider = $searchModel->baseSearch(Yii::$app->request->queryParams);
        $oneDataProvider = $searchModel->limitOneBaseSearch(Yii::$app->request->queryParams);
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'month',
                'year',
                'type',
                'selectionName',
                'selectionCaretPos'
            ]
        );
        $params = $searchModel->setParams($params);
        $eventSelectors = [
            'change' => '.summary-journal-form select'
        ];
        $typesOfDisplay = $searchModel->getTypesOfDisplay();

        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.summary-journal-form', $eventSelectors);

        $script = Yii::$app->view->render(
            "/summary-journal/data/script",
            [
                'numberOfDays' => $searchModel->getDaysByMonths($params['year'])[$params['month']],
                'monthName' => $searchModel->getMonths()[$params['month']],
                'lastYearIncome' => $searchModel->getIncomeForLastYear($params['year'], $params['month']),
                'isDetailed' => false
            ]
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'oneDataProvider' => $oneDataProvider,
            'script' => $script,
            'summaryJournalController' => $this,
            'params' => $params,
            'months' => $searchModel->getMonths(),
            'years' => $searchModel->getYears(),
            'numberOfDays' => $searchModel->getDaysByMonths($params['year'])[$params['month']],
            'monthName' => $searchModel->getMonths()[$params['month']],
            'submitFormOnInputEvents' => $submitFormOnInputEvents,
            'typesOfDisplay' => $typesOfDisplay
        ]);
    }

    /**
     * Main detailed summary journal action
     * 
     * @return string
     */
    public function actionIndexDetailed()
    {
        $searchModel = new BalanceHolderSummaryDetailedSearch();
        $dataProvider = $searchModel->baseSearch(Yii::$app->request->queryParams);
        $oneDataProvider = $searchModel->limitOneBaseSearch(Yii::$app->request->queryParams);
        $entityHelper = new EntityHelper();
        $params = $entityHelper->makeParamsFromRequest(
            [
                'month',
                'year',
                'type',
                'selectionName',
                'selectionCaretPos'
            ]
        );
        $params = $searchModel->setParams($params);
        $eventSelectors = [
            'change' => '.summary-journal-form select'
        ];
        $typesOfDisplay = $searchModel->getTypesOfDisplay();

        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.summary-journal-form', $eventSelectors);

        $script = Yii::$app->view->render(
            "/summary-journal/data/script",
            [
                'numberOfDays' => $searchModel->getDaysByMonths($params['year'])[$params['month']],
                'monthName' => $searchModel->getMonths()[$params['month']],
                'lastYearIncome' => $searchModel->getIncomeForLastYear($params['year'], $params['month']),
                'isDetailed' => true,
                'lastMonthIncome' => $searchModel->getIncomeForLastMonth($params['year'], $params['month'])
            ]
        );

        return $this->render('index-detailed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'oneDataProvider' => $oneDataProvider,
            'script' => $script,
            'summaryJournalController' => $this,
            'params' => $params,
            'months' => $searchModel->getMonths(),
            'years' => $searchModel->getYears(),
            'numberOfDays' => $searchModel->getDaysByMonths($params['year'])[$params['month']],
            'monthName' => $searchModel->getMonths()[$params['month']],
            'submitFormOnInputEvents' => $submitFormOnInputEvents,
            'typesOfDisplay' => $typesOfDisplay
        ]);
    }

    /**
     * Renders all balanceholder addresses
     * 
     * @param BalanceHolderSummarySearch $searchModel
     * @param ActiveDataProvider $dataProvider
     * @param array $params
     * @return string
     */
    public function renderBalanceAddresses($searchModel, $dataProvider, $params)
    {
        $searchModel = new BalanceHolderSummarySearch();
        list($year, $month) = [$params['year'], $params['month']];
        $daysNumber = $searchModel->getDaysByMonths($year)[$month];
        $timestampEnd =  $searchModel->getTimestampByYearMonthDay($year, $month, $daysNumber, false);
        $timestampStart =  $searchModel->getTimestampByYearMonthDay($year, $month, '01', true);

        return $this->renderPartial('/summary-journal/data/balance-addresses', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'timestampStart' => $timestampStart,
            'timestampEnd' => $timestampEnd,
            'year' => $year,
            'month' => $month
        ]);
    }
    
     /**
     * Renders all balanceholder addresses
     * 
     * @param BalanceHolderSummaryDetailedSearch $searchModel
     * @param ActiveDataProvider $dataProvider
     * @param array $params
     * @return string
     */
    public function renderBalanceAddressesDetailed($searchModel, $dataProvider, $params)
    {
        $searchModel = new BalanceHolderSummaryDetailedSearch();
        list($year, $month) = [$params['year'], $params['month']];
        $daysNumber = $searchModel->getDaysByMonths($year)[$month];
        $timestampEnd =  $searchModel->getTimestampByYearMonthDay($year, $month, $daysNumber, false);
        $timestampStart =  $searchModel->getTimestampByYearMonthDay($year, $month, '01', true);

        return $this->renderPartial('/summary-journal/data/balance-addresses-detailed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'timestampStart' => $timestampStart,
            'timestampEnd' => $timestampEnd,
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * Renders incomes by addresses
     * 
     * @param BalanceHolderSummarySearch $searchModel
     * @param ActiveDataProvider $dataProvider
     * @param array $params
     * @return string
     */
    public function renderIncomesByAddresses($searchModel, $dataProvider, $params)
    {
        $searchModel = new BalanceHolderSummarySearch();
        $days = $searchModel->getDaysByMonths($params['year']);
        list($year, $month) = [$params['year'], $params['month']];
        $daysNumber = $searchModel->getDaysByMonths($year)[$month];
        $timestamp =  $searchModel->getTimestampByYearMonthDay($year, $month, $daysNumber, false);
        $months = $searchModel->getMonths();
        $data = $searchModel->getIncomesAggregatedData($dataProvider, $year, $month, $daysNumber, $timestamp);

        return $this->renderPartial('/summary-journal/data/incomes-by-addresses', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'days' => $days[$params['month']],
            'timestamp' => $timestamp,
            'year' => $year,
            'month' => $month,
            'data' => $data
        ]);
    }
    
     /**
     * Renders incomes by mashines
     * 
     * @param BalanceHolderSummaryDetailedSearch $searchModel
     * @param ActiveDataProvider $dataProvider
     * @param array $params
     * @return string
     */
    public function renderIncomesByMashines($searchModel, $dataProvider, $params)
    {
        $searchModel = new BalanceHolderSummaryDetailedSearch();
        $days = $searchModel->getDaysByMonths($params['year']);
        list($year, $month) = [$params['year'], $params['month']];
        $daysNumber = $searchModel->getDaysByMonths($year)[$month];
        $timestamp =  $searchModel->getTimestampByYearMonthDay($year, $month, $daysNumber, false);
        $months = $searchModel->getMonths();
        $data = $searchModel->getMashineIncomesAggregatedData($dataProvider, $year, $month, $daysNumber, $timestamp);

        return $this->renderPartial('/summary-journal/data/incomes-by-mashines', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'days' => $days[$params['month']],
            'timestamp' => $timestamp,
            'year' => $year,
            'month' => $month,
            'data' => $data
        ]);
    }


    /**
     * Renders serial column
     * 
     * @param int $recordQuantity
     * @return string
     */
    public function renderSerialColumn($recordQuantity)
    {
        return $this->renderPartial('/summary-journal/data/serial-column', [
            'recordQuantity' => $recordQuantity,
        ]);
    }

    /**
     * Renders month days
     * 
     * @param array $params
     * @return string
     */
    public function renderMonthDays($params)
    {
        $searchModel = new BalanceHolderSummarySearch();
        $days = $searchModel->getDaysByMonths($params['year']);
        $months = $searchModel->getMonths();
        $years = $searchModel->getYears();
        $monthTimestamps = $searchModel->getTimestampByYearMonth($params['year'], $params['month']);
    
        return $this->renderPartial('/summary-journal/data/month-days', [
            'numberOfDays' => $days[$params['month']],
            'monthName' => $months[$params['month']],
            'year' => $years[$params['year']],
            'timestampStart' => $monthTimestamps['start'],
            'timestampEnd' => $monthTimestamps['end']
        ]);
    }

    /**
     * Renders incomes summary by addresses
     *
     * @return string
     */
    public function renderIncomesSummaryByAddresses()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-summary-addresses'
        ]);
    }

    /**
     * Renders average summary by addresses
     *
     * @return string
     */
    public function renderAverageSummaryByAddresses()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-average-summary-addresses'
        ]);
    }

    /**
     * Renders average mashine summary by addresses
     *
     * @return string
     */
    public function renderAverageMashineSummaryByAddresses()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-average-mashine-summary-addresses'
        ]);
    }

    /**
     * Renders average citizens summary by addresses
     *
     * @return string
     */
    public function renderAverageCitizensSummaryByAddresses()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-average-citizens-summary-addresses'
        ]);
    }

    /**
     * Renders consolidated summary by addresses
     *
     * @return string
     */
    public function renderConsolidatedSummaryByAddresses()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-consolidated-summary-addresses'
        ]);
    }

    /**
     * Renders expectation summary by addresses
     *
     * @return string
     */
    public function renderExpectation()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-expectation'
        ]);
    }

    /**
     * Renders expectation summary by balanceholders
     *
     * @return string
     */
    public function renderExpectationByBalanceHoders()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-expectation-by-balance-holders'
        ]);
    }

    /**
     * Renders idle days by addresses
     *
     * @return string
     */
    public function renderIdleDays()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' =>  'table-idle-days'
        ]);
    }

    /**
     * Renders idle damages by addresses
     *
     * @return string
     */
    public function renderIdleDamages()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-idle-damages'
        ]);
    }

    /**
     * Renders summary conclusion by addresses
     *
     * @return string
     */    
    public function renderSummaryConclusion()
    {

        return $this->renderPartial('/summary-journal/data/base_table', [
            'class' => 'table-summary-conclusion'
        ]);
    }

    /**
     * Renders form
     *
     * @param array $params
     * @param array $months
     * @param array $years
     * @param array $typesOfDisplay
     * @return string
     */
    public function renderForm($params, $months, $years, $typesOfDisplay)
    {
        return $this->renderPartial('/summary-journal/form', [
            'params' => $params,
            'months' => $months,
            'years' => $years,
            'typesOfDisplay' => $typesOfDisplay
        ]);
    }
}
