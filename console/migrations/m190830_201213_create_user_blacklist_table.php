<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_blacklist}}`.
 */
class m190830_201213_create_user_blacklist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_blacklist', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'company_id' => $this->integer(),
            'author_id' => $this->integer(),
            'comment' => $this->text(),
            'unix_time_offset' => $this->integer()
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-user_blacklist-user_id',
            'user_blacklist',
            [
                'user_id',
            ],
            false
        );

        // creates index for column `company_id`
        $this->createIndex(
            'idx-user_blacklist-company_id',
            'user_blacklist',
            [
                'company_id',
            ],
            false
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-user_blacklist-company_id',
            'user_blacklist',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-user_blacklist-user_id',
            'user_blacklist',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_blacklist');
    }
}
