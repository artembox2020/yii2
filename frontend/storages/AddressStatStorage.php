<?php

namespace frontend\storages;
use frontend\models\WmMashineDataSearch;
use frontend\models\BalanceHolder;
use Yii;
use frontend\services\globals\DateTimeHelper;
use frontend\models\AddressImeiData;
use frontend\models\BalanceHolderSummarySearch;
use frontend\services\globals\QueryOptimizer;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use frontend\models\AddressBalanceHolder;

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
    const YELLOW_LOADING_VALUE = 75;
    const RED_LOADING_VALUE = 90;

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
     * @param int|bool $companyId
     *
     * @return array
     */
    public function getAddressesLoading(int $start, int $end, $other, $companyId = false): array
    {
        $balanceHolder = new BalanceHolder();

        $data = [];
        $addresses = $balanceHolder->getAddressesByTimestamps($start, $end, false, $other, $companyId);
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
    public function getAddressesLoadingForGoogleGraphByTimestamps(int $start, int $end, string $other, array $options, $companyId = false): array
    {
        $balanceHolder = new BalanceHolder();
        $data = $this->getAddressesLoading($start, $end, $other, $companyId);
        $addresses = $balanceHolder->getAddressesByTimestamps($start, $end, false, $other, $companyId);
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

    /**
     * Main average address loading method by address id and time intervals
     *
     * @param int $addressId
     * @param int $created_at
     * @param int $is_deleted
     * @param int $deleted_at
     * @param int $start
     * @param int $end
     *
     * @return int
     */
    public function getAverageAddressLoading(int $addressId, int $created_at, int $is_deleted, int $deleted_at, int $start, int $end)
    {
        $dateTimeHelper = new DateTimeHelper();

        if ($start < $created_at) {

            if ($created_at >= $end) {

                return 0;
            }

            $start = $dateTimeHelper->getDayBeginningTimestamp($start);
        }

        if ($is_deleted && $deleted_at <= $end) {

            if ($deleted_at <= $start) {

                return 0;
            }

            $end = $dateTimeHelper->getDayBeginningTimestamp($end);
        }

        if ($end - $start < self::TYPE_STEP) {

            return 0;
        }

        $baseStart = $start;
        $baseValue = 0;
        $baseDays = 0;

        $queryString = "SELECT end, value FROM address_load_data WHERE address_id = :address_id AND ".
                       "start = :start AND end <= :end ORDER BY end DESC LIMIT 1;";
        $bindValues = [':address_id' => $addressId, ':start' => $start, ':end' => $end];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);
        $item = $command->queryOne();

        if (!empty($item)) {

            if ($item['end'] == $end) {

                return $item['value'];
            }

            $start = $item['end'];
            $baseValue = $item['value'];
            $baseDays = (int)(($item['end'] - $item['start']) / self::TYPE_STEP);
        }

        $averageLoading = $this->getAverageAddressLoadingBase($addressId, $start, $end);
        $averageDays = (int)(($end - $start) / self::TYPE_STEP);

        return (int)(($baseValue*$baseDays + $averageLoading * $averageDays) / ($baseDays + $averageDays));
    }

    /**
     * Base average address loading method by address id and time intervals
     *
     * @param int $addressId
     * @param int $start
     * @param int $end
     *
     * @return int
     */
    public function getAverageAddressLoadingBase($addressId, $start, $end)
    {
        $loadings = [];
        $currentStamp = $start;

        while ($currentStamp < $end) {
            $loadings[$currentStamp] = $this->getAddressLoading($addressId, $currentStamp, $currentStamp + self::TYPE_STEP);
            $currentStamp += self::TYPE_STEP;
        }

        $currentStamp -= self::TYPE_STEP;
        $sum = 0;
        $numberSteps = 0;

        while ($currentStamp >= $start) {
            ++$numberSteps;
            $sum += $loadings[$currentStamp];
            $this->putToAddressLoadData($addressId, $currentStamp, $end, (int)($sum/$numberSteps));
            $currentStamp -= self::TYPE_STEP;
        }

        $averageLoad = empty($numberSteps) ? 0 : (int)($sum/$numberSteps);

        return $averageLoad;
    }

    /**
     * Puts average address load data to `address_load_data` table
     *
     * @param int $addressId
     * @param int $start
     * @param int $end
     * @param int $value
     */
    public function putToAddressLoadData($addressId, $start, $end, $value)
    {
        $queryString = "DELETE FROM address_load_data WHERE address_id = :address_id ".
                       "AND start = :start AND end <= :end";
        $bindValues = [':address_id' => $addressId, ':start' => $start, ':end' => $end];
        Yii::$app->db->createCommand($queryString)->bindValues($bindValues)->execute();

        $queryString = "INSERT INTO address_load_data(address_id, start, end, value) ".
                        "VALUES(:address_id, :start, :end, :value)";
        $bindValues = [':address_id' => $addressId, ':start' => $start, ':end' => $end, ':value' => $value];
        Yii::$app->db->createCommand($queryString)->bindValues($bindValues)->execute();
    }

    /**
     * Gets average address loading for last days by address id and returns as rating array data
     *
     * @param int $lastDays
     * @param int $topRating
     * @param int $start
     * @param int $end
     * @param int|bool $companyId
     *
     * @return int
     */
    public function getAverageAddressesLoadingByLastDays($lastDays, $topRating, $companyId = false)
    {
        $dateTimeHelper = new DateTimeHelper();
        $balanceHolder = new BalanceHolder();
        $end = $dateTimeHelper->getDayBeginningTimestamp(time());
        $start = $end - $lastDays * self::TYPE_STEP;
        $addresses = $balanceHolder->getAddressesByTimestamps($start, $end, false, false, $companyId);

        $addressesAverageLoadings = [];

        foreach ($addresses as $address) {
            $addressesAverageLoadings[] = [
                'id' => $address['id'],
                'addressLabel' => $address['address'].', '.$address['floor'],
                'address' => $address['address'],
                'floor' => $address['floor'],
                'value' => $this->getAverageAddressLoading(
                    $address['id'], $address['created_at'], $address['is_deleted'], $address['deleted_at'], $start, $end
                )
            ];
        }

        ArrayHelper::multisort($addressesAverageLoadings, ['value'], [SORT_DESC]);

        $addressLoadings = array_slice($addressesAverageLoadings, 0, $topRating);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $addressLoadings,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    /**
     * Gets address average load field view by item and field name
     *
     * @param array $model
     * @param stirng $field
     *
     * @return string
     */
    public function getAddressAverageLoadingFieldByItem($model, $field)
    {
        switch ($field) {
            case "address":
                $address = AddressBalanceHolder::find()
                            ->where(['address' => $model['address'], 'floor' => $model['floor']])
                            ->one();

                $link = empty($address) ? '<div>'.$model['addressLabel'].'</div>' : Yii::$app->commonHelper->link($address);

                return $link;
            case "value":

                return '<div>'.$model['value'].'</div>';
        }
    }

    /**
     * Gets average address loading class label by item
     *
     * @param array $model
     *
     * @return string
     */
    public function getRowClassByItem($model)
    {
        $class = 'address-average-loading-cell';

        if ($model['value'] >= self::RED_LOADING_VALUE) {
            $class .= ' red';
        } else if ($model['value'] >= self::YELLOW_LOADING_VALUE) {
            $class .= ' yellow';
        }

        return $class;
    }
}