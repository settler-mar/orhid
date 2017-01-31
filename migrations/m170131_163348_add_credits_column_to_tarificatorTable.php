<?php

use yii\db\Migration;

class m170131_163348_add_credits_column_to_tarificatorTable extends Migration
{
    public function up()
    {
        $this->addColumn('tarificatorTable', 'credits', $this->integer()->notNull());
    }

    public function down()
    {
        echo "m170131_163348_add_credits_column_to_tarificatorTable cannot be reverted.\n";
        $this->dropColumn('tarificatorTable', 'credits');
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
