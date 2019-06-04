<?php

namespace frontend\storages;
use frontend\models\WmMashineDataSearch;
use frontend\models\BalanceHolder;
use frontend\models\Jlog;
use frontend\models\JlogSearch;
use frontend\models\JlogInitSearch;
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
    const MAX_POINTS_NUMBER = 7500;

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
        $jlogSearch = new JlogSearch();
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

        $monitoringStep = 900;
        $allAddressesNotInTouchItems = $jlogSearch->getModemNotInTouchItemsByAddresses(
            $addressesInfo, $start, $end, $monitoringStep
        );

        foreach ($addressesInfo as $addressInfo) {
            $addressString = $addressInfo['address'].", ".$addressInfo['floor'];

            if (array_key_exists($addressString, $allAddressesNotInTouchItems)) {
                $notInTouchItems = $allAddressesNotInTouchItems[$addressString];
            } else {
                 $notInTouchItems = [];
            }

            $itemIndex = 0;
            $itemsCount = count($notInTouchItems);
            $beforeCondition = true;
            $afterCondition = true;
            $levelSignalIteration = false;
            $stamp = $baseStart;
            $levelSignal = self::MIN_LEVEL_SIGNAL;
            $nextLevelSignal = $levelSignal;
            $prevLevelSignal = $levelSignal;

            for ($baseStart = $start; $baseStart <= $end; $baseStart += self::STEP) {
                list($levelSignal, $itemIndex, $stamp, $nextLevelSignal, $levelSignalIteration, $prevLevelSignal) = 
                $this->getIterationLevelSignal(
                    $baseStart, $baseStart + self::STEP, $itemIndex,
                    $notInTouchItems, $itemsCount, $jlogSearch, $addressString,
                    $levelSignalIteration, $stamp, $levelSignal, $nextLevelSignal, $prevLevelSignal
                );

                $index = ($baseStart - $start) / self::STEP;
                $lines[$index][$iterationCounter] = $levelSignal;
            }

            $titles[] = $addressInfo['address'].(!empty($addressInfo['floor']) ? ", {$addressInfo['floor']}": "");
            ++$iterationCounter;
        }

        $lines = $this->optimizeLines($lines, $iterationCounter);

        return ['titles' => $titles, 'lines' => $lines, 'options' => $options];
    }

    /**
     * merges the lines points and unsets the last line
     * 
     * @param array $lines
     * @param int $iterationCounter
     */
    public function optimizeLines($lines, $iterationCounter)
    {
        $numberOfPoints = ($iterationCounter - 1) * count($lines);

        if ($numberOfPoints > self::MAX_POINTS_NUMBER) {
            $lines = $this->mergeLines($lines);
        }
        $linesCount = count($lines);

        if ($linesCount > 0) {
            unset($lines[$linesCount-1]);
        }

        return $lines;
    }
    
    /**
     * Gets iteration level signal
     * 
     * @param int $start
     * @param int $end
     * @param int $index
     * @param array $items
     * @param int $count
     * @param \frontend\models\JlogSearch $jlogSearch
     * @param string $address
     * @param bool $levelSignalIteration
     * @param int $stamp
     * @param int $levelSignal
     * @param int $nextLevelSignal
     * @param int $prevLevelSignal
     * 
     * @return array
     */
    public function getIterationLevelSignal(
        $start, $end, $index, $items, $count, $jlogSearch, $address,
        $levelSignalIteration, $stamp, $levelSignal, $nextLevelSignal, $prevLevelSignal
    )
    {
        $beforeCondition = $count && ($end < $items[$index]['start']);
        $afterCondition = $count && ( $start >= $items[$index]['end']);
        $condition = $beforeCondition || $afterCondition ||!$count;

        if ($condition) {

            if (!$levelSignalIteration) {
                $levelSignal = $jlogSearch->getLastLevelSignalByAddressAndTimestamp($address, $end);

                if (is_null($levelSignal)) {
                    list($stamp, $levelSignal) = $this->getNextLevelSignal($address, $start);
                } else {
                    $stamp = $start;
                }

                $nextLevelSignal = $levelSignal;
                $levelSignalIteration = true;
            } elseif ($stamp < $end) {
                $levelSignal = $nextLevelSignal;
                list($stamp, $nextLevelSignal) = $this->getNextLevelSignal($address, $start);

                if (is_null($nextLevelSignal)) {
                    $nextLevelSignal = self::MIN_LEVEL_SIGNAL;
                }

                if ($stamp < $end) {
                    $levelSignal = $nextLevelSignal;
                }
            } elseif ($levelSignal == self::MIN_LEVEL_SIGNAL) {
                $levelSignal = $prevLevelSignal;
            }

            if ($afterCondition && $index + 1 < $count) {
                ++$index;
            }
        } else {
            if ($levelSignal != self::MIN_LEVEL_SIGNAL) {
                $prevLevelSignal = $levelSignal;
            }

            $levelSignal = self:: MIN_LEVEL_SIGNAL;
        }

        return [$levelSignal, $index, $stamp, $nextLevelSignal, $levelSignalIteration, $prevLevelSignal];
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

    /**
     * Gets next initialization level signal
     * 
     * @param string $addressString
     * @param int $start
     * 
     * @return array
     */
    public function getNextLevelSignal($addressString, $start)
    {
        do {
            $queryString = "SELECT packet, unix_time_offset FROM j_log WHERE ".
                           "type_packet = :type_packet AND address LIKE :addressString ".
                           "AND unix_time_offset > :start ".
                           "ORDER BY unix_time_offset ASC LIMIT 1";

            $bindValues = [
                ':start' => $start,
                ':type_packet' => Jlog::TYPE_PACKET_INITIALIZATION,
                ':addressString' => '%'.$addressString.'%'
            ];

            $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);
            $item = $command->queryOne();

            if ($item) {
                $parser = new CParser();
                $levelSignal = $parser->getLevelSignal($item['packet']);

                if (!is_null($levelSignal)) {

                    return [$item['unix_time_offset'], $levelSignal];
                }

                $start = $item['unix_time_offset'];
            } else {

                return [JlogSearch::INFINITY, null];
            }
        } while(true);
    }
}