<?php

namespace frontend\storages;

interface GraphStorageInterface
{
	public function init();

	/**
	 * @param array
	 * @param string $selector
	 * @return string
	 */
	public function drawHistogram(array $data, string $selector);
}
