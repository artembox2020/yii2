<?php

namespace frontend\storages;
use frontend\models\WmMashineDataSearch;
use frontend\models\BalanceHolder;
use Yii;
use frontend\services\globals\DateTimeHelper;

/**
 * Class MashineStatStorage
 * @package frontend\storages;
 */
class MashineStatStorage implements MashineStatStorageInterface
{
    const DATE_FORMAT = 'd.m.Y';
    const INPUT_DATE_FORMAT = 'Y-m-d';
    const TYPE_STEP = 3600 * 24;
    const MAX_INTERVAL_STEPS = 30;

    public $dateFormat;
    public $step;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->dateFormat = self::DATE_FORMAT;
        $this->step = self::TYPE_STEP;
    }

    /**
     * Sets step regarding time intervals
     * 
     * @param int $start
     * @param int $end
     */
    public function setStepByTimestamps(int $start, int $end)
    {
        $diff = ceil(($end - $start) / DateTimeHelper::DAY_TIMESTAMP);

        if ($diff > self::MAX_INTERVAL_STEPS) {
            $this->step = ceil($diff * $this->step / self::MAX_INTERVAL_STEPS);
            $this->step = ceil($this->step / DateTimeHelper::DAY_TIMESTAMP) * DateTimeHelper::DAY_TIMESTAMP;
        }
    }

    /**
     * Aggregates all, green, grey, at work, with errors wm mashines by time intervals
     * 
     * @param int $start
     * @param int $end
     * 
     * @return array
     */
    public function aggregateAllGreenGreyWorkErrorByTimestamps(int $start, int $end): array
    {
        $mashineData = new WmMashineDataSearch();
        $historyBeginning = $mashineData->getHistoryDayBeginning();

        if ($historyBeginning > $start) {
            $start = $historyBeginning;
        }

        $data = [];

        while ($start < $end) {
            $currEnd = $start + $this->step;
            $all = $mashineData->getAllMashinesCountByTimestamps($start, $currEnd);
            $green = $mashineData->getGreenMashinesCountByTimestamps($start, $currEnd);
            $grey = $mashineData->getGreyMashinesCountByTimestamp($start, $currEnd);
            $work = $mashineData->getWorkMashinesCountByTimestamps($start, $currEnd);
            $error = $mashineData->getErrorMashinesCountByTimestamps($start, $currEnd);

            $data[$start] = [
                'all' => $all,
                'green' => $green,
                'grey' => $grey,
                'work' => $work,
                'error' => $error
            ];

            $start = $currEnd;
        }

        return $data;
    }

    /**
     * Aggregates current all, green, grey, at work with errors wm mashines
     * 
     * @param int $timestamp
     * 
     * @return array
     */
    public function aggregateAllGreenGreyWorkErrorCurrent(int $timestamp): array
    {
        $mashineData = new WmMashineDataSearch();
        $data = [];

        $all = $mashineData->getAllCurrentMashinesCount();
        $green = $mashineData->getGreenCurrentMashinesCount();
        $grey = $mashineData->getGreyCurrentMashinesCount();
        $work = $mashineData->getWorkCurrentMashinesCount();
        $error = $mashineData->getErrorCurrentMashinesCount();

        $data[$timestamp] = [
            'all' => $all,
            'green' => $green,
            'grey' => $grey,
            'work' => $work,
            'error' => $error
        ];

        return $data;
    }

    /**
     * Aggregates balance holders incomes
     * 
     * @param int $start
     * @param int $end
     * 
     * @return array
     */
    public function aggregateBalanceHoldersIncomes($start, $end)
    {
        $incomes = BalanceHolder::getIncomesByAllBalanceHolders($start, $end);

        if ($end - $start > DateTimeHelper::DAY_TIMESTAMP) {
            $key = date($this->dateFormat, $start).'-'.date($this->dateFormat, $end - DateTimeHelper::DAY_TIMESTAMP);
        } else {
            $key = date($this->dateFormat, $start);
        }

        return [$key => $incomes];
    }

    /**
     * Aggregates current baalnce holders incomes
     * 
     * @param int $timestamp
     * 
     * @return array
     */
    public function aggregateCurrentBalanceHoldersIncomes($timestamp)
    {
        $incomes = BalanceHolder::getCurrentIncomesByAllBalanceHolders();
        $key = date($this->dateFormat, $timestamp);

        return [$key => $incomes];
    }

    /**
     * Aggregates balance holders incomes for Google Graph
     * 
     * @param int $start
     * @param int $end
     * @param array $opitons
     * 
     * @return array
     */
    public function aggregateBalanceHoldersIncomesForGoogleGraph($start, $end, $options)
    {
        if ($start != $end) {
            $data = $this->aggregateBalanceHoldersIncomes($start, $end);
        } else {
            $data = $this->aggregateCurrentBalanceHoldersIncomes($start);
        }

        $key = array_keys($data)[0];
        $lines = [0 => array_merge([$key], array_values($data[$key]))];
        $titles = array_merge([''], array_keys($data[$key]));

        return ['titles' => $titles, 'lines' => $lines, 'options' => $options];
    }

    /**
     * Aggregates all, green, grey, at work, with errors wm mashines for google by time intervals
     * 
     * @param int $start
     * @param int $end
     * @param array $options
     * 
     * @return array
     */
    public function aggregateAllGreenGreyWorkErrorForGoogleGraphByTimestamps(int $start, int $end, array $options): array
    {
        if ($start != $end) {
            $data = $this->aggregateAllGreenGreyWorkErrorByTimestamps($start, $end);
        } else {
            $data = $this->aggregateAllGreenGreyWorkErrorCurrent($end);
        }

        $titles = [
            Yii::t('graph', ''),
            Yii::t('graph', 'All WM'),
            Yii::t('graph', 'WM In Touch'),
            Yii::t('graph', 'WM Not In Touch'),
            Yii::t('graph', 'WM At Work'),
            Yii::t('graph', 'WM With Errors')
        ];

        $lines = [];

        foreach ($data as $key=>$item) {
            $lines[] = [
                date($this->dateFormat, $key),
                $item['all'],
                $item['green'],
                $item['grey'],
                $item['work'],
                $item['error']
            ];
        }

        return ['titles' => $titles, 'lines' => $lines, 'options' => $options];
    }

    /**
     * Gets initial params
     * 
     * @param string $selector
     * @param string $action
     * @param int|string $active
     * 
     * @return array
     */
    public function getInitialParams($selector, $action, $active): array
    {
        $timestamps = $this->getTimeIntervalsByDropDown($active, false);

        return [
            'start' => $timestamps['start'],
            'end' => $timestamps['end'],
            'selector' => $selector,
            'action' => $action,
            'active' => $active
        ];
    }

    /**
     * Gets time intervals items
     * 
     * @return array
     */
    public function getTimeIntervalsLines(): array
    {

        return [
            'current day' => Yii::t('graph', 'Current'),
            'current week' => Yii::t('graph', 'Current week'),
            'current month' => Yii::t('graph', 'Current month'),
            'current quarter' => Yii::t('graph', 'Current quarter'),
            'current year' => Yii::t('graph', 'Current year'),
            'any' => Yii::t('graph', 'Any date'),
        ];
    }

    /**
     * Gets from date value by active
     * 
     * @param string $active
     * 
     * @return string|bool
     */
    public function getFromDateByActive($active)
    {
        $dateParts = explode("*", $active);

        if (count($dateParts) < 2) {

            return false;
        }

        if (!is_numeric($dateParts[0]) || !is_numeric($dateParts[1])) {

            return false;
        }

        return date(self::INPUT_DATE_FORMAT, $dateParts[0]);
    }

    /**
     * Gets to date value by active
     * 
     * @param string $active
     * 
     * @return string|bool
     */
    public function getToDateByActive($active)
    {
        $dateParts = explode("*", $active);

        if (count($dateParts) < 2) {

            return false;
        }

        if (!is_numeric($dateParts[0]) || !is_numeric($dateParts[1])) {

            return false;
        }

        return date(self::INPUT_DATE_FORMAT, $dateParts[1]);
    }

    /**
     * Gets date options value by active
     * 
     * @param string $active
     * 
     * @return string
     */
    public function getDateOptionsByActive($active)
    {
        $dateParts = explode("*", $active);
        if (count($dateParts) == 2) {

            return $dateParts[0];
        }

        return $active;
    }

    /**
     * Gets date value by active
     * 
     * @param string $active
     * 
     * @return string|bool
     */
    public function getDateByActive($active)
    {
        $dateParts = explode("*", $active);
        if (count($dateParts) == 2) {

            return $dateParts[1];
        }

        return false;
    }

    /**
     * Gets time intervals by dropdown and date
     * 
     * @param string $active
     * @param string $date
     * 
     * @return array
     */
    public function getTimeIntervalsByDropDown(string $active, string $date, $other = null): array
    {
        $dateTimeHelper = new DateTimeHelper();
        $currentTimestamp = $dateTimeHelper->getRealUnixTimeOffset(0);
        $end = $dateTimeHelper->getDayBeginningTimestamp($currentTimestamp);

        switch ($active) {
            case "current day":
                $start = $end;
                break;
            case "current week":
                $start = $dateTimeHelper->getWeekBeginningByTimestamp($currentTimestamp);
                $end = time();
                break;
            case "current month":
                $start = $dateTimeHelper->getMonthBeginningByTimestamp($currentTimestamp);
                $end = time();
                break;
            case "current quarter":
                $start = $dateTimeHelper->getQuarterBeginningByTimestamp($currentTimestamp);
                $end = time();
            case "current year":
                $start = $dateTimeHelper->getYearBeginningByTimestamp($currentTimestamp);
                $end = time();
                break;
            case "any":
                $timestamp = strtotime($date);

                if ($timestamp > time()) {
                    $timestamp = time();
                }

                $start = $dateTimeHelper->getDayBeginningTimestamp($timestamp);
                $end = $start + DateTimeHelper::DAY_TIMESTAMP;

                if ($end > time()) {
                    $end = time();
                }

                $active.= "*".$date;
                break;
        }

        return ['start' => $start, 'active' => $active, 'end' => $end, 'other' => $other];
    }

    /**
     * Gets time intervals by dates between
     * 
     * @param string $active
     * @param string $dateStart
     * @param string $dateEnd
     * 
     * @return array
     */
    public function getTimeIntervalsByDatesBetween($active, $dateStart, $dateEnd, $other = null)
    {
        $dateTimeHelper = new DateTimeHelper();
        $timestamp = strtotime($dateStart);
        $start = $dateTimeHelper->getDayBeginningTimestamp($timestamp);

        $timestamp = strtotime($dateEnd);
        $end = $dateTimeHelper->getDayBeginningTimestamp($timestamp) + DateTimeHelper::DAY_TIMESTAMP;

        if (time() < $end) {
            $end = time();
        }

        return ['start' => $start, 'end' => $end, 'active' => $active, 'other' => $other];
    }
}