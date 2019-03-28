<?php

namespace frontend\storages;

/**
 * Interface MashineStatStorageInterface
 * @package frontend\storages;
 */
interface MashineStatStorageInterface
{
    /**
     * Aggregates all, green, grey, at work, with errors wm mashines by time intervals
     * 
     * @param int $start
     * @param int $end
     * 
     * @return array
     */
	public function aggregateAllGreenGreyWorkErrorByTimestamps(int $start, int $end): array;
	
	/**
     * Aggregates current all, green, grey, at work with errors wm mashines
     * 
     * @param int $timestamp
     * 
     * @return array
     */
    public function aggregateAllGreenGreyWorkErrorCurrent(int $timestamp): array;

    /**
     * Gets initial params
     * 
     * @param string $selector
     * @param string $action
     * @param int|string $active
     * 
     * @return array
     */
    public function getInitialParams($selector, $action, $active): array;

    /**
     * Gets time intervals items
     * 
     * @return array
     */
    public function getTimeIntervalsLines(): array;
    
    /**
     * Gets from date value by active
     * 
     * @param string $active
     * 
     * @return string|bool
     */
    public function getFromDateByActive($active);
    
    /**
     * Gets to date value by active
     * 
     * @param string $active
     * 
     * @return string|bool
     */
    public function getToDateByActive($active);
    
    /**
     * Gets date options value by active
     * 
     * @param string $active
     * 
     * @return string
     */
    public function getDateOptionsByActive($active);
    
    /**
     * Gets date value by active
     * 
     * @param string $active
     * 
     * @return string|bool
     */
    public function getDateByActive($active);
    
    /**
     * Gets time intervals by dropdown and date
     * 
     * @param string $active
     * @param string $date
     * 
     * @return array
     */
    public function getTimeIntervalsByDropDown(string $active, string $date): array;
    
    /**
     * Gets time intervals by dates between
     * 
     * @param string $active
     * 
     * @return array
     */
    public function getTimeIntervalsByDatesBetween($active);
}
