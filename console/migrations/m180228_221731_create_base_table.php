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
            'date' => $this->integer(100),
            'imei' => $this->string(100),
            'gsmSignal' => $this->string(100),
            'fvVer' => $this->string(100),
            'numBills' => $this->string(100),
            'billAcceptorState' => $this->string(100),
            'id_hard' => $this->string(100),
            'type' => $this->string(100),
            'collection' => $this->string(100),
            'ZigBeeSig' => $this->string(100),
            'billCash' => $this->string(100),
            'tariff' => $this->string(100),
            'event' => $this->string(100),
            'edate' => $this->integer(100),
            'billModem' => $this->string(100),
            'sumBills' => $this->string(100),
            'ost' => $this->string(100),
            'numDev' => $this->string(100),
            'devSignal' => $this->string(100),
            'statusDev' => $this->string(100),
            'colGel' => $this->string(100),
            'colCart' => $this->string(100),
            'price' => $this->string(100),
            'timeout' => $this->string(100),
            'doorpos' => $this->string(100),
            'doorled' => $this->string(100),
            'kpVer' => $this->string(100),
            'srVer' => $this->string(100),
            'mTel' => $this->string(100),
            'sTel' => $this->string(100),
            'ksum' => $this->string(100)
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
