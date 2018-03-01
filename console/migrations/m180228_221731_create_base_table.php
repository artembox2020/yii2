<?php

use yii\db\Migration;

/**
 * Handles the creation of table `base`.
 */
class m180228_221731_create_base_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('base', [
            'id' => $this->primaryKey(),
            'date' => $this->integer(),
            'imei' => $this->integer(100),
            'gsmSignal' => $this->integer(100),
            'fvVer' => $this->integer(100),
            'numBills' => $this->integer(100),
            'billAcceptorState' => $this->integer(100),
            'id_hard' => $this->integer(100),
            'type' => $this->integer(100),
            'collection' => $this->integer(100),
            'ZigBeeSig' => $this->integer(100),
            'billCash' => $this->integer(100),
            'event' => $this->integer(100),
            'edate' => $this->integer(100),
            'billModem' => $this->integer(100),
            'sumBills' => $this->integer(100),
            'ost' => $this->integer(100),
            'numDev' => $this->integer(100),
            'devSignal' => $this->integer(100),
            'statusDev' => $this->integer(100),
            'colGel' => $this->integer(100),
            'colCart' => $this->integer(100),
            'price' => $this->integer(100),
            'timeout' => $this->integer(100),
            'doorpos' => $this->integer(100),
            'doorled' => $this->integer(100)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('base');
    }
}
