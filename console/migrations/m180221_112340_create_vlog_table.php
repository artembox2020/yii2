<?php

use yii\db\Migration;

/**
 * Handles the creation of table `vlog`.
 */
class m180221_112340_create_vlog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('vlog', [
            'id' => $this->primaryKey(),
            'date' => $this->dateTime()->null(),
            'text' => $this->string(250)->null(),
            'id_hard' => $this->string(100)->null(),
            'imei' => $this->string(100)->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('vlog');
    }
}
