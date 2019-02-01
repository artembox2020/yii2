<?php

use yii\db\Migration;

/**
 * Class m190201_031918_add_index_to_jlog_table
 */
class m190201_031918_add_index_to_jlog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-j_log-address-date-type_packet-imei-date_end-company_id`
        $this->dropIndex(
            'idx-j_log-address-date-type_packet-imei-date_end-company_id',
            'j_log'
        );
    }
}
