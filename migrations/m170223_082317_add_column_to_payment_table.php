<?php

use yii\db\Migration;

class m170223_082317_add_column_to_payment_table extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'credits', $this->integer()->defaultValue(0)->notNull());
    }

    public function down()
    {
        echo "m170223_082317_add_column_to_payment_table cannot be reverted.\n";
        $this->dropColumn('user', 'credits');
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
