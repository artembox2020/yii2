<?php

use yii\db\Migration;

/**
 * Handles the creation of table `key_storage_item`.
 */
class m180221_101218_create_key_storage_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('key_storage_item', [
            'key' => $this->string(128)->notNull(),
            'value' => $this->text()->notNull(),
            'comment' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('key_storage_item');
    }
}
