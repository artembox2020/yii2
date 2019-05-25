<?php

use yii\db\Migration;

/**
 * Handles the creation of table `j_log`.
 */
class m180806_211100_create_j_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('j_log', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'imei_id' => $this->integer(11)->null(),
            'packet' => $this->text()->null(),
            'type_packet' => $this->string(250)->null(),
            'imei' => $this->string(250)->null(),
            'address' => $this->string(250)->null(),
            'events' => $this->text()->null(),
            'date' => $this->string(128),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('j_log');
    }
}
