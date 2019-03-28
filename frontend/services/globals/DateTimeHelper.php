<?php

namespace frontend\services\globals;
use frontend\models\Jlog;

/**
 * Class DateTimeHelper
 * @package frontend\services\globals
 */
class DateTimeHelper
{
    const DAY_TIMESTAMP = 3600*24;
    const WEEK_LENGTH = 7;
    const MONTHS_NUMBER = 12;

    /**
     * Gets the real timestamp by given timestamp
     * 
     * @param int $unixTimeOffset
     * @return int
     */
    public function getRealUnixTimeOffset(int $unixTimeOffset): int
    {
        if ($unixTimeOffset < self::DAY_TIMESTAMP) {

            return time() + Jlog::TYPE_TIME_OFFSET;
        }

        return $unixTimeOffset;
    }

    /**
     * Gets day beginning timestamp by given timestamp
     * 
     * @param int $timestamp
     * @return int
     */
    public function getDayBeginningTimestamp($timestamp)
    {
        $year = date('Y', $timestamp);
        $month = date('m', $timestamp);
        $day = date('d', $timestamp);

        return  strtotime($year.'-'.$month.'-'.$day.' 00:00:00');
    }

    /**
     * Gets week beginning timestamp by given timestamp
     * 
     * @param int $timestamp
     * @return int
     */
    public function getWeekBeginningByTimestamp($timestamp)
    {
        $timestamp = $this->getDayBeginningTimestamp($timestamp);
        $day = date('N', $timestamp) - 1;

        if ($day == 0) {
            $day = self::WEEK_LENGTH;
        }

        return $timestamp - self::DAY_TIMESTAMP* $day;
    }

    /**
     * Gets month beginning timestamp by given timestamp
     * 
     * @param int $timestamp
     * @return int
     */
    public function getMonthBeginningByTimestamp($timestamp)
    {
        $timestamp = $this->getDayBeginningTimestamp($timestamp);
        $year = date("Y", $timestamp);
        $month = date("m", $timestamp);
        $monthTimestamp = strtotime($year."-".$month."-01");

        if ($monthTimestamp == $timestamp) {

            if ((int)$month == 1) {
                $month = '12';
                --$year;
            } else {
                --$month;
            }

            return strtotime($year."-".$month."-01");
        }

        return $monthTimestamp;
    }

    /**
     * Gets quarter(3 months ago) beginning timestamp by given timestamp
     * 
     * @param int $timestamp
     * @return int
     */
    public function getQuarterBeginningByTimestamp($timestamp)
    {
        $monthBeginning = $this->getMonthBeginningByTimestamp($timestamp);
        $month = date("m", $monthBeginning);
        $year = date("Y", $monthBeginning);
        
        if ((int)$month <= 2) {
            $month = self::MONTHS_NUMBER - 2 + $month;
            --$year;
        } else {
            $month -= 2;
        }

        return strtotime($year."-".$month."-01");
    }

    /**
     * Gets year beginning timestamp by given timestamp
     * 
     * @param int $timestamp
     * @return int
     */
    public function getYearBeginningByTimestamp($timestamp)
    {
        $yearTimestamp = strtotime(date("Y", $timestamp)."-01-01 00:00:00");

        if ($timestamp - $yearTimestamp < self::DAY_TIMESTAMP) {
            $prevYear = (int)date("Y") - 1;

            return strtotime(date($prevYear."-01-01"));
        }

        return $yearTimestamp; 
    }
}