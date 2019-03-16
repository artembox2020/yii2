<?php

namespace frontend\storages;
use frontend\models\WmMashineDataSearch;
use Yii;

class MashineStatStorage implements MashineStatStorageInterface
{
    const DATE_FORMAT = 'd.m.Y';
    public function aggregateActiveWorkErrorByTimestamps($start, $end, $step)
    {
        $mashineData = new WmMashineDataSearch();
        $data = [];

        while ($start < $end) {
            $active = $mashineData->getActiveMashinesCountByTimestamps($start, $end);
            $work = $mashineData->getAtWorkMashinesCountByTimestamps($start, $end);
            $error = $mashineData->getErrorMashinesCountByTimestamps($start, $end);
            $data[$start] = ['active' => $active, 'work' => $work, 'error' => $error];
            $start += $step;
        }

        return $data;
    }

    public function aggregateActiveWorkErrorForGoogleGraphByTimestamps($start, $end, $step)
    {
        $data = $this->aggregateActiveWorkErrorByTimestamps($start, $end, $step);

        $titles = [
            Yii::t('graph', 'Date'),
            Yii::t('graph', 'All WM'),
            Yii::t('graph', 'At Work'),
            Yii::t('graph', 'With Errors')
        ];

        $lines = [];

        foreach ($data as $key=>$item) {

            $lines[] = [
                date(self::DATE_FORMAT, $key),
                $item['active'],
                $item['work'],
                $item['error']
            ];
        }

        return ['titles' => $titles, 'lines' => $lines];
    }
}