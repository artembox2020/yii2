<?php

use yii\db\Migration;

/**
 * Handles the creation of table `devices`.
 */
class m180221_100217_create_devices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('devices', [
            'id' => $this->primaryKey(),
            'id_dev' => $this->string(100),
            'name' => $this->string(250),
            'organization' => $this->string(250),
            'city' => $this->string(250),
            'adress' => $this->string(250),
            'name_cont' => $this->string(250),
            'tel_cont' => $this->string(50),
            'operator' => $this->string(100),
            'n_operator' => $this->string(100),
            'kp' => $this->string(100),
            'kps' => $this->string(50),
            'balans' =>$this->string(100)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('devices');
    }
}
