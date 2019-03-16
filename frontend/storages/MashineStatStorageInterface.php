<?php

namespace frontend\storages;

interface MashineStatStorageInterface
{
	public function aggregateActiveWorkErrorByTimestamps($start, $end, $step);
}
