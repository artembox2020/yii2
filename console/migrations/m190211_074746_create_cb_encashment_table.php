<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cb_encashment`.
 */
class m190211_074746_create_cb_encashment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cb_encashment', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'address_id' => $this->integer()->notNull(),
            'imei_id' => $this->integer()->notNull(),
            'imei' => $this->string(50),
            'device' => $this->string(11),
            'unix_time_offset' => $this->integer(),
            'collection_counter' => $this->float(),
            'last_collection_counter' => $this->float()->null(),
            'status' => $this->integer(),
            'fireproof_counter_hrn' => $this->float(),
            'notes_billiards_pcs' => $this->double(),
            'banknote_face_values' => $this->string(),
            'amount_of_coins' => $this->double(),
            'coin_face_values' => $this->string(),
            'recount_amount' => $this->float()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-cb_encashment-imei_id',
            'cb_encashment',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-cb_encashment-imei_id',
            'cb_encashment',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );

        // creates index for column `company_id`
        $this->createIndex(
            'idx-cb_encashment-company_id',
            'cb_encashment',
            'company_id'
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-cb_encashment-company_id',
            'cb_encashment',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        // creates index for column `address_id`
        $this->createIndex(
            'idx-cb_encashment-address_id',
            'cb_encashment',
            'address_id'
        );

        // add foreign key for table `cb_encashment`
        $this->addForeignKey(
            'fk-cb_encashment-address_id',
            'cb_encashment',
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
        $this->dropForeignKey(
            'fk-cb_encashment-address_id',
            'cb_encashment'
        );

        // drops index for column `address_id`
        $this->dropIndex(
            'idx-cb_encashment-address_id',
            'cb_encashment'
        );

        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-cb_encashment-imei_id',
            'cb_encashment'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-cb_encashment-imei_id',
            'cb_encashment'
        );

        $this->dropTable('cb_encashment');
    }
}
