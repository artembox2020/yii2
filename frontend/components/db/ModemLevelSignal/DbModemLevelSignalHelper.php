<?php

namespace frontend\components\db\ModemLevelSignal;

use Yii;
use frontend\components\db\DbCommandHelper;
use frontend\models\Company;
use frontend\models\Jlog;
use frontend\models\JlogSearch;
use frontend\models\AddressBalanceHolder;
use frontend\services\parser\CParser;

/**
 * Class DbModemLevelSignalHelper
 * @package frontend\components\db\ModemLevelSignal
 */
class DbModemLevelSignalHelper extends DbCommandHelper
{
    /**
     * Erases data by address id and timestamp intervals
     * 
     * @param int $addressId
     * @param int $start
     * @param int $end
     * 
     * @return int
     */
    public function eraseData($addressId, $start, $end)
    {
        $queryString = "DELETE FROM modem_level_signal WHERE address_id = :address_id ";
        $queryString .= "AND start >= :start AND start <= :end AND end >= :start AND end <= :end";
        $bindValues = [':address_id' => $addressId, ':start' => $start, ':end' => $end];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return $command->execute();
    }

    /**
     * Inserts row into table `modem_level_signal`
     * 
     * @param int $imeiId
     * @param int $addressId
     * @param int $balanceHolderId
     * @param int $companyId
     * @param int $start
     * @param int $end
     * @param int $levelSignal
     * 
     * @return int
     */
    public function insertData($imeiId, $addressId, $balanceHolderId, $companyId, $start, $end, $levelSignal)
    {
        $queryString = "INSERT INTO modem_level_signal(imei_id, address_id, balance_holder_id, company_id, start, end, level_signal)".
                        "VALUES(:imei_id, :address_id, :balance_holder_id, :company_id, :start, :end, :level_signal)";

        $bindValues = [
            ':imei_id' => $imeiId,
            ':address_id' => $addressId,
            ':balance_holder_id' => $balanceHolderId,
            ':company_id' => $companyId,
            ':start' => $start,
            ':end' => $end,
            ':level_signal' => $levelSignal
        ];

        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        if ($command->execute()) {
            $queryString = "SELECT id FROM modem_level_signal WHERE ".
                           "imei_id = :imei_id AND start = :start AND end = :end AND level_signal = :level_signal LIMIT 1";

            $bindValues = [':imei_id' => $imeiId, ':start' => $start, ':end' => $end, ':level_signal' => $levelSignal];
            $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);
            $id = $command->queryScalar();
        }

        return $id ?? false;
    }

    /**
     * Updates row of the table `modem_level_signal`
     * 
     * @param int $id
     * @param int $start
     * @param int $end
     * @param int $levelSignal
     * 
     * @return int
     */
    public function updateData($id, $start, $end, $levelSignal)
    {
        $queryString = "UPDATE modem_level_signal SET start = :start, end = :end, level_signal = :level_signal ".
                       "WHERE id = :id LIMIT 1";
        $bindValues = [':id' => $id, ':start' => $start, ':end' => $end, ':level_signal' => $levelSignal];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return $command->execute();
    }

    /**
     * Gets data by address and timestamps 
     * 
     * @param int $addressId
     * @param int $start
     * @param int $end
     * 
     * @return array
     */
    public function getDataByAddressIdAndTimestamps($addressId, $start, $end)
    {
        $queryString = "SELECT start, end, level_signal FROM modem_level_signal WHERE ".
                       "address_id = :address_id AND start < :end AND end > :start ORDER BY start ASC";
        $bindValues = [':address_id' => $addressId, ':start' => $start, ':end' => $end];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return $command->queryAll();
    }

    /**
     * Gets addresses by company and timestamps and within address list given
     * 
     * @param int $start
     * @param int $end
     * @param int $companyId
     * @param string $addressIds
     * 
     * @return array
     */
    public function getAddressesByTimestampsAndCompanyId($start, $end, $companyId, $addressIds)
    {
        $select = "id, name, address, floor";
        $bInst = Company::find()->where(['id' => $companyId])->limit(1)->one();
        $inst = new AddressBalanceHolder();
        $this->getExistingUnitQueryByTimestamps($start, $end, $inst, $bInst, 'company_id', $select);

        if (!empty($addressIds)) {
            $this->queryString .= " AND id IN (".$addressIds.")";
        }

        $this->queryString.= " ORDER BY name ASC";

        return $this->getItems();
    }

    /**
     * Gets initialization data by address and start and end timestamps
     * 
     * @param string $addressString
     * @param int $start
     * @param int $end
     * 
     * @return array
     */
    public function getInitializationData($addressString, $start, $end)
    {
        $this->queryString = "SELECT packet, unix_time_offset FROM j_log WHERE ".
                             "unix_time_offset >= :start AND unix_time_offset <= :end ".
                             "AND type_packet = :type_packet AND address LIKE :addressString ".
                             "ORDER BY unix_time_offset ASC";

        $this->bindValues = [
            ':start' => $start,
            ':end' => $end,
            ':type_packet' => Jlog::TYPE_PACKET_INITIALIZATION,
            ':addressString' => '%'.$addressString.'%'
        ];

        return $this->getItems();
    }
}