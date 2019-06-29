<?php

use yii\db\Migration;

/**
 * Class m190629_124604_add_column_company_to_customer_card_table
 */
class m190629_124604_add_column_company_to_customer_card_table extends Migration
{
    public function up()
    {
        $this->addColumn(
            'customer_cards',
            'company_id',
            $this->integer()
        );
        $this->createIndex(
            'idx-customer_cards-company_id',
            'customer_cards',
            'company_id'
        );
        $this->addForeignKey(
            'fk-customer_cards-company_id',
            'customer_cards',
            'company_id',
            'company',
            'id'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-customer_cards-company_id',
            'customer_cards'
        );
        $this->dropIndex(
            'idx-customer_cards-company_id',
            'customer_cards'
        );
        $this->dropColumn(
            'customer_cards',
            'company_id'
        );
    }
}
