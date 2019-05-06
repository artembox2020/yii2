<?php

namespace frontend\components\db\ModemLevelSignal;

use Yii;
use frontend\components\db\DbCommandHelper;
use frontend\models\Company;
use frontend\models\AddressBalanceHolder;

/**
 * Class DbModemLevelSignalHelper
 * @package frontend\components\db\ModemLevelSignal
 */
class DbModemLevelSignalHelper extends DbCommandHelper
{
    public function getTest()
    {

        return 'test';
    }

    public function eraseData($addressId, $start, $end)
    {
        $queryString = "DELETE FROM modem_level_signal WHERE address_id = :address_id ";
        $queryString .= "AND start >= :start AND start <= :end AND end >= :start AND end <= :end";
        $bindValues = [':address_id' => $addressId, ':start' => $start, ':end' => $end];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return $command->execute();
    }

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

    public function updateData($id, $start, $end, $levelSignal)
    {
        $queryString = "UPDATE modem_level_signal SET start = :start, end = :end, level_signal = :level_signal ".
                       "WHERE id = :id LIMIT 1";
        $bindValues = [':id' => $id, ':start' => $start, ':end' => $end, ':level_signal' => $levelSignal];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return $command->execute();
    }

    public function getDataByAddressIdAndTimestamps($addressId, $start, $end)
    {
        $queryString = "SELECT start, end, level_signal FROM modem_level_signal WHERE ".
                       "address_id = :address_id AND start >= :start AND end <= :end ORDER BY start ASC";
        $bindValues = [':address_id' => $addressId, ':start' => $start, ':end' => $end];
        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return $command->queryAll();
    }

    public function getAddressesByTimestampsAndCompanyId($start, $end, $companyId)
    {
        $select = "id, name";
        $bInst = Company::find()->where(['id' => $companyId])->limit(1)->one();
        $inst = new AddressBalanceHolder();
        $this->getExistingUnitQueryByTimestamps($start, $end, $inst, $bInst, 'company_id', $select);

        return $this->getItems();//Yii::$app->db->createCommand($this->queryString)->bindValues($this->bindValues)->rawSql;
    }
}