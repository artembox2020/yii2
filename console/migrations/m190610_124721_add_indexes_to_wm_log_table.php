<?php

use yii\db\Migration;

/**
 * Class m190610_124721_add_indexes_to_wm_log_table
 */
class m190610_124721_add_indexes_to_wm_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `created_at`
        $this->createIndex(
            'idx-wm_log-created_at',
            'wm_log',
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
        // deletes index `idx-wm_log-created_at`
        $this->dropIndex(
            'idx-wm_log-created_at',
            'wm_log'
        );
    }
}
