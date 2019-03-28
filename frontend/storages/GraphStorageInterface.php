<?php

namespace frontend\storages;

/**
 * Interface GraphStorageInterface
 * @package frontend\storages;
 */
interface GraphStorageInterface
{
    /**
     * Initializes object instance
     */
	public function init();

	/**
     * Draws histogram by input data, inside container by selector
     * 
     * @param array $data
     * @param string $selector
     */ 
	public function drawHistogram(array $data, string $selector);
}
