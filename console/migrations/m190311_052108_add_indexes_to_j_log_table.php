<?php

use yii\db\Migration;

/**
 * Class m190311_052108_add_indexes_to_j_log_table
 */
class m190311_052108_add_indexes_to_j_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `address`
        $this->createIndex(
            'idx-j_log-address',
            'j_log',
            [
                'address'
            ],
            false
        );

        // creates index for columns `company_id, type_packet, address, unix_tme_offset`
        $this->createIndex(
            'idx-j_log-company_id-type_packet-address-unix_time_offset',
            'j_log',
            [
                'company_id',
                'type_packet',
                'address',
                'unix_time_offset'
            ],
            false
        );

        // creates index for column `packet`        
        $this->createIndex(
            'idx-j_log-packet',
            'j_log',
            [
                'packet'
            ],
            false
        );

        // creates index for columns `company_id, packet, imei, type_packet`
        $this->createIndex(
            'idx-j_log-company_id-packet-imei-type_packet',
            'j_log',
            [
                'company_id',
                'packet',
                'imei',
                'type_packet'
            ],
            false
        );

        // creates index for columns `company_id, packet, imei, type_packet, unix_time_offset`
        $this->createIndex(
            'idx-j_log-company_id-packet-imei-type_packet-unix_time_offset',
            'j_log',
            [
                'company_id',
                'packet',
                'imei',
                'type_packet',
                'unix_time_offset'
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-j_log-address`
        $this->dropIndex(
            'idx-j_log-address',
            'j_log'
        );

        // deletes index `idx-j_log-company_id-type_packet-address-unix_time_offset`
        $this->dropIndex(
            'idx-j_log-company_id-type_packet-address-unix_time_offset',
            'j_log'
        );

        // deletes index `idx-j_log-packet`
        $this->dropIndex(
            'idx-j_log-packet',
            'j_log'
        );

        // deletes index `idx-j-log-company_id-packet-imei-type_packet`
        $this->dropIndex(
            'idx-j_log-company_id-packet-imei-type_packet',
            'j_log'
        );

        // deletes index `idx-j-log-company_id-packet-imei-type_packet-unix_time_offset` 
        $this->dropIndex(
            'idx-j_log-company_id-packet-imei-type_packet-unix_time_offset',
            'j_log'
        );
    }
}
