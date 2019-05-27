<?php

namespace frontend\storages;
use frontend\models\WmMashineDataSearch;
use frontend\models\BalanceHolder;
use Yii;
use yii\helpers\ArrayHelper;
use frontend\services\globals\DateTimeHelper;
use frontend\services\globals\Entity;
use frontend\services\parser\CParser;
use frontend\components\db\ModemLevelSignal\DbModemLevelSignalHelper;
use console\controllers\ModemLevelSignalController;

/**
 * Class ModemStatStorage
 * @package frontend\storages;
 */
class ModemStatStorage extends MashineStatStorage
{
    const STEP = 300;
    const MIN_LEVEL_SIGNAL = -128;
    const MAX_POINTS_NUMBER = 2500;

    /**
     * Aggregates modem level signals for google graph
     * 
     * @param int $start
     * @param int $end
     * @param int $other
     * @param array $options
     * 
     * @return array
     */
    public function aggregateModemLevelSignalsForGoogleGraph($start, $end, $other, $options)
    {
        $dbHelper = new DbModemLevelSignalHelper();
        $entity = new Entity();
        $companyId = $entity->getCompanyId();
        $addressesInfo = $dbHelper->getAddressesByTimestampsAndCompanyId($start, $end, $companyId, $other);

        $lines = [];
        $titles = [''];

        for ($baseStart = $start; $baseStart <= $end; $baseStart+= self::STEP) {
            $item = [];
            $item[] = date("d.m.Y H:i", $baseStart + self::STEP);

            foreach ($addressesInfo as $addressInfo) {
                $item[]= self::MIN_LEVEL_SIGNAL;
            }

            $lines[] = $item;
        }

        $iterationCounter = 1;

        foreach ($addressesInfo as $addressInfo) {
            $data = $dbHelper->getDataByAddressIdAndTimestamps($addressInfo['id'], $start, $end);
            foreach ($data as $item) {
                $stamp = $item['start'];

                if ($stamp < $start) {
                    $stamp = $start;
                }

                $stampEnd = $item['end'];

                if ($stampEnd > $end) {
                    $stampEnd = $end;
                }

                $index = ($stamp - $start) / self::STEP;
                $number = ($stampEnd - $stamp) / self::STEP;

                for ($i = $index; $i < $index + $number; ++$i) {
                    $lines[$i][$iterationCounter] = $item['level_signal'];
                }
            }

            $titles[] = $addressInfo['address'].(!empty($addressInfo['floor']) ? ", {$addressInfo['floor']}": "");
            ++$iterationCounter;
        }
        $numberOfPoints = ($iterationCounter - 1) * count($lines);

        if ($numberOfPoints > self::MAX_POINTS_NUMBER) {
            $lines = $this->mergeLines($lines);
        }
        $linesCount = count($lines);

        if ($linesCount > 0) {
            unset($lines[$linesCount-1]);
        }

        return ['titles' => $titles, 'lines' => $lines, 'options' => $options];
    }

    /**
     * Merges the points which stand on the one line
     * 
     * @param array $lines
     * 
     * @return array
     */
    public function mergeLines($lines)
    {
        $indexStart = -1;
        $indexEnd = -1;
        $values = [];

        for ($i = 0; $i < count($lines); ++$i) {
            if ($indexStart < 0) {
                for ($j = 1; $j < count($lines[$i]); ++$j) {
                    $values[$j-1] = $lines[$i][$j];
                }
                $indexStart = $i;
                $indexEnd = $i;
            } else {
                $indEqual = true;
                for ($j = 1; $j < count($lines[$i]); ++$j) {
                    if ($values[$j-1] != $lines[$i][$j]) {
                        $values[$j-1] = $lines[$i][$j];
                        $indEqual = false;
                    }
                }

                if (!$indEqual) {
                    if ($indexEnd - $indexStart > 1) {
                        for ($i = $indexStart + 1; $i < $indexEnd; ++$i) {
                            unset($lines[$i]);
                        }

                        return $this->mergeLines(array_values($lines));
                    }
                    $indexStart = $i;
                    $indexEnd = $i;
                } else {
                    $indexEnd = $i;
                }
            }
        }

        if ($indexEnd - $indexStart > 1) {
            for ($i = $indexStart + 1; $i < $indexEnd; ++$i) {
                unset($lines[$i]);
            }

            return array_values($lines);
        }

        return $lines;
    }

    /**
     * Aggregates  current day modem level signals for google graph
     * 
     * @param int $other
     * @param array $options
     * 
     * @return array
     */
    public function aggregateCurrentModemLevelSignalsForGoogleGraph($other, $options)
    {
        $dateHelper = new DateTimeHelper();
        $start = $dateHelper->getDayBeginningTimestamp($end=time());

        return $this->aggregateModemLevelSignalsForGoogleGraph($start, $end, $other, $options);
    }

    /**
     * Makes address points
     * 
     * @param int $start
     * @param int $end
     * @param int $other
     * 
     * @return array
     */
    public function makeAddressPoints($start, $end, $other)
    {
        $dbHelper = new DbModemLevelSignalHelper();
        $entity = new Entity();
        $companyId = $entity->getCompanyId();
        $addressesInfo = $dbHelper->getAddressesByTimestampsAndCompanyId($start, $end, $companyId, '');
        $addressIds = ArrayHelper::getColumn($addressesInfo, 'id');
        $resultArray = [];

        if (!empty($other)) {
            $other = explode(",", $other);
            $resultArray = array_intersect($addressIds, $other);
        }

        $data = [];

        foreach ($addressesInfo as $addressInfo) {
            $isChecked = in_array($addressInfo['id'], $resultArray);
            $data[] = [
                'checked' => $isChecked,
                'id' => $addressInfo['id'],
                'name' => $addressInfo['address']
            ];
        }

        return Yii::$app->view->render("/dashboard/templates/address-points", ['data' => $data]);
    }

    /**
     * Gets initialization data from `j_log` table
     * 
     * @param string $addressString
     * @param int $start
     * @param int $end
     * 
     * @return array
     */
    public function getInitializationData($addressString, $start, $end)
    {
        $dbHelper = new DbModemLevelSignalHelper();
        $parser = new CParser();

        $data = $dbHelper->getInitializationData($addressString, $start, $end);
        $outputData = [];

        foreach ($data as $item) {
            $levelSignal = $parser->getLevelSignal($item['packet']);
            $outputData[] = ['levelSignal' => $levelSignal, 'unix_time_offset' => $item['unix_time_offset']];
        }

        return $outputData;
    }
}