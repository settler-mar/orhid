<?php

use yii\db\Migration;

class m170222_162909_add_comum_to_pay_table extends Migration
{
    public function up()
    {
      $this->addColumn('payments', 'method', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        echo "m170222_162909_add_comum_to_pay_table cannot be reverted.\n";
        $this->dropColumn('payments', 'method');
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
