<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wm_log`.
 */
class m181023_140247_create_wm_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('wm_log', [
            'id' => $this->primaryKey(),
            'date' => $this->integer(),
            'imei' => $this->string(50),
            'unix_time_offset' => $this->integer(),
            'number' => $this->integer(),
            'signal' => $this->integer(),
            'status' => $this->integer(),
            'price' => $this->float(),
            'account_money' => $this->float(),
            'washing_mode' => $this->integer(),
            'wash_temperature' => $this->integer(),
            'spin_type' => $this->integer(),
            'prewash' => $this->double(),
            'rinsing' => $this->double(),
            'intensive_wash' => $this->double(),
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
        $this->dropTable('wm_log');
    }
}
