<?php

use yii\db\Migration;

/**
 * Class m190305_100405_update_indexes_to_j_log_table
 */
class m190305_100405_update_indexes_to_j_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates column `unix_time_offset`
        $this->addColumn('j_log', 'unix_time_offset', $this->integer());

        // deletes index `idx-j_log-address-date-type_packet-imei-date_end-company_id`
        $this->dropIndex(
            'idx-j_log-address-date-type_packet-imei-date_end-company_id',
            'j_log'
        );

        // creates index `idx-j_log-unix_time_offset`
        $this->createIndex(
            'idx-j_log-unix_time_offset',
            'j_log',
            [
                'unix_time_offset'
            ],
            false
        );

        // creates index `idx-j_log-company_id-type_packet-address`
        $this->createIndex(
            'idx-j_log-company_id-type_packet-address',
            'j_log',
            [
                'company_id',
                'type_packet',
                'address'
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // creates index `idx-j_log-address-date-type_packet-imei-date_end-company_id`
        $this->createIndex(
            'idx-j_log-address-date-type_packet-imei-date_end-company_id',
            'j_log',
            [
                'address',
                'date',
                'type_packet',
                'imei',
                'date_end',
                'company_id'
            ],
            false
        );

        // deletes index `idx-j_log-unix_time_offset`
        $this->dropIndex(
            'idx-j_log-unix_time_offset',
            'j_log'
        );

        // deletes index `idx-j_log-company_id-type_packet-address`
        $this->dropIndex(
            'idx-j_log-company_id-type_packet-address',
            'j_log'
        );

        // deletes column `unix_time_offset`
        $this->dropColumn('j_log', 'unix_time_offset');
    }
}
