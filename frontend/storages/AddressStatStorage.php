<?php

namespace frontend\storages;
use frontend\models\WmMashineDataSearch;
use frontend\models\BalanceHolder;
use Yii;
use frontend\services\globals\DateTimeHelper;
use frontend\models\AddressImeiData;
use frontend\models\BalanceHolderSummarySearch;
use frontend\services\globals\QueryOptimizer;

/**
 * Class AddressStatStorage
 * @package frontend\storages;
 */
class AddressStatStorage
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
     * Aggregates addresses loading by timestamps
     *
     * @param int $start
     * @param int $end
     * @param string $other
     *
     * @return array
     */
    public function getAddressesLoading(int $start, int $end, $other): array
    {
        $balanceHolder = new BalanceHolder();

        $data = [];
        $addresses = $balanceHolder->getAddressesByTimestamps($start, $end, false, $other);
        $firstIteration = true;

        while ($start < $end || $firstIteration) {
            $firstIteration = false;
            $dataItem = [];
            $step = $start < $end ? self::TYPE_STEP : 0;

            foreach ($addresses as $address) {
                $dataItem[$address['id']] = $this->getAddressLoading($address['id'], $start, $start + $step);
            }

            $data[$start] = $dataItem;
            $start += $step;
        }

        return $data;
    }

    /**
     * Gets address loading as percents
     *
     * @param int $addressId
     * @param int $start
     * @param int $end
     *
     * @return int
     */
    public function getAddressLoading($addressId, $start, $end)
    {
        $addressImeiData = new AddressImeiData();
        $bhSummarySearch = new BalanceHolderSummarySearch();
        $imeiId = $addressImeiData->getImeiIdByAddressTimestamp($addressId, $end);
        $mashinesQuery = $bhSummarySearch->getAllMashinesQueryByTimestamps($start, $end, $imeiId);
        $mashines = QueryOptimizer::getItemsByQuery($mashinesQuery);
        $loading = 0;

        foreach ($mashines as $mashine) {
            $loading += $mashine->getWorkPercents($start, $end);
        }

        return empty($count = count($mashines)) ? 0 : $bhSummarySearch->parseFloat($loading / $count, 0);
    }

    /**
     * Aggregates addresses loading for google graph
     *
     * @param int $start
     * @param int $end
     * @param string $other
     * @param array $options
     *
     * @return array
     */
    public function getAddressesLoadingForGoogleGraphByTimestamps(int $start, int $end, string $other, array $options): array
    {
        $balanceHolder = new BalanceHolder();
        $data = $this->getAddressesLoading($start, $end, $other);
        $addresses = $balanceHolder->getAddressesByTimestamps($start, $end, false, $other);
        $titles = [''];
        $staticTitles = [''];

        foreach ($addresses as $address) {
            $titles[] = Yii::t('graph', $address['address'].' '.$address['name']);
        }

        $lines = [];

        foreach ($data as $key=>$item) {
            $line = [date($this->dateFormat, $key)];
            foreach ($item as $value) {
                $line[] = $value;
            }
            $lines[] = $line;
        }

        return ['titles' => $titles, 'staticTitles' => $staticTitles, 'lines' => $lines, 'options' => $options];
    }
}