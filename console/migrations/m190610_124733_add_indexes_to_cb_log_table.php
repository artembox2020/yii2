<?php

use yii\db\Migration;

/**
 * Class m190610_124733_add_indexes_to_cb_log_table
 */
class m190610_124733_add_indexes_to_cb_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `created_at`
        $this->createIndex(
            'idx-cb_log-created_at',
            'cb_log',
            [
                'created_at'
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-cb_log-created_at`
        $this->dropIndex(
            'idx-cb_log-created_at',
            'cb_log'
        );
    }
}
