<?php

use yii\db\Migration;

/**
 * Handles the creation of table `imei_data`.
 * Has foreign keys to the tables:
 *
 * - `imei`
 */
class m180405_205639_create_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('imei_data', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'imei' => $this->string(255),
            'level_signal' => $this->integer(),
            'on_modem_account' => $this->integer(),
            'in_banknotes' => $this->integer(),
            'money_in_banknotes' => $this->integer(),
            'fireproof_residue' => $this->integer(),
            'price_regim' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-imei_data-imei_id',
            'imei_data',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-imei_data-imei_id',
            'imei_data',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-imei_data-imei_id',
            'imei_data'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-imei_data-imei_id',
            'imei_data'
        );

        $this->dropTable('imei_data');
    }
}
