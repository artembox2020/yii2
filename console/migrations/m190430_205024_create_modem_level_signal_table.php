<?php

use yii\db\Migration;

/**
 * Handles the creation of table `modem_level_signal`
 */
class m190430_205024_create_modem_level_signal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('modem_level_signal', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer(11)->notNull(),
            'address_id' => $this->integer(11),
            'company_id' => $this->integer(11)->notNull(),
            'balance_holder_id' => $this->integer(11),
            'start' => $this->integer(11)->notNull(),
            'end' => $this->integer(11)->notNull(),
            'level_signal' => $this->integer(11),
            'is_calculated' => $this->boolean()
        ]);

        // add foreign key for table `modem_level_signal`
        $this->addForeignKey(
            'fk-modem_level_signal-company_id',
            'modem_level_signal',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-modem_level_signal-imei_id',
            'modem_level_signal',
            [
                'imei_id'
            ],
            false
        );
        
        // creates index for column `address_id`
        $this->createIndex(
            'idx-modem_level_signal-address_id',
            'modem_level_signal',
            [
                'address_id'
            ],
            false
        );
        
        // creates index for column `balance_holder_id`
        $this->createIndex(
            'idx-modem_level_signal-balance_holder_id',
            'modem_level_signal',
            [
                'balance_holder_id'
            ],
            false
        );

        // creates index for column `company_id`
        $this->createIndex(
            'idx-modem_level_signal-company_id',
            'modem_level_signal',
            [
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
        // drops foreign key for table `modem_level_signal`
        $this->dropForeignKey(
            'fk-modem_level_signal-company_id',
            'modem_level_signal'
        );

        // deletes index `idx-modem_level_signal-company_id`
        $this->dropIndex(
            'idx-modem_level_signal-company_id',
            'modem_level_signal'
        );

        // deletes index `idx-modem_level_signal-balance_holder_id`
        $this->dropIndex(
            'idx-modem_level_signal-balance_holder_id',
            'modem_level_signal'
        );

        // deletes index `idx-modem_level_signal-address_id`
        $this->dropIndex(
            'idx-modem_level_signal-address_id',
            'modem_level_signal'
        );

        // deletes index `idx-modem_level_signal-imei_id`
        $this->dropIndex(
            'idx-modem_level_signal-imei_id',
            'modem_level_signal'
        );

        $this->dropTable('modem_level_signal');
    }
}
