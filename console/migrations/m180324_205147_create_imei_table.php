<?php

use yii\db\Migration;

/**
 * Handles the creation of table `imei`.
 * Has foreign keys to the tables:
 *
 * - `address_balance_holder`
 */
class m180324_205147_create_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('imei', [
            'id' => $this->primaryKey(),
            'imei' => $this->string(50),
            'address_id' => $this->integer()->notNull(),
            'type_packet' => $this->string(),
            'imei_central_board' => $this->string(50),
            'firmware_version' => $this->string(),
            'type_bill_acceptance' => $this->string(),
            'serial_number_kp' => $this->string(),
            'phone_module_number' => $this->string(),
            'crash_event_sms' => $this->string(),
            'critical_amount' => $this->integer(),
            'time_out' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `address_id`
        $this->createIndex(
            'idx-imei-address_id',
            'imei',
            'address_id'
        );

        // add foreign key for table `address_balance_holder`
        $this->addForeignKey(
            'fk-imei-address_id',
            'imei',
            'address_id',
            'address_balance_holder',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `address_balance_holder`
        $this->dropForeignKey(
            'fk-imei-address_id',
            'imei'
        );

        // drops index for column `address_id`
        $this->dropIndex(
            'idx-imei-address_id',
            'imei'
        );

        $this->dropTable('imei');
    }
}
