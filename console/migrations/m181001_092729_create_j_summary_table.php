<?php

use yii\db\Migration;

/**
 * Handles the creation of table `j_summary`.
 */
class m181001_092729_create_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates j_summary table
        $this->createTable('j_summary', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer(11)->notNull(),
            'start_timestamp' => $this->integer(11)->notNull(),
            'end_timestamp' => $this->integer(11)->notNull(),
            'income' => $this->double(2)->null(),
            'created' => $this->integer(11)->null(),
            'active' => $this->integer(11)->null(),
            'deleted' => $this->integer(11)->null(),
            'all' => $this->integer(11)->null(),
            'idleHours' => $this->integer(11)->null()
        ]);

        // creates index for columns `imei_id, start_timestamp, end_timestamp`
        $this->createIndex(
            'idx-j_summary-imei_id-start_timestamp-end_timestamp',
            'j_summary',
            [
                'imei_id',
                'start_timestamp',
                'end_timestamp',
            ],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-j_summary-imei_id-start_timestamp-end_timestamp` for j_summary table
        $this->dropIndex(
            'idx-j_summary-imei_id-start_timestamp-end_timestamp',
            'j_summary'
        );

        // deletes j_summary table
        $this->dropTable('j_summary');
    }
}
