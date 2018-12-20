<?php

use yii\db\Migration;

/**
 * Class m181218_085842_storage_history_table
 */
class m181218_085842_storage_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('storage_history', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'address_id' => $this->integer()->notNull(),
            'imei_id' => $this->integer()->notNull(),
            'date_transfer_from_storage' => $this->integer(),
            'number_device' => $this->integer(),
            'type' => $this->string(),
            'ping' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('storage_history');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181218_085842_storage_history_table cannot be reverted.\n";

        return false;
    }
    */
}
