<?php

use yii\db\Migration;

class m170215_134821_add_coumn_table_user extends Migration
{
    public function up()
    {
      $this->addColumn('user', 'credits', $this->integer()->defaultValue(0));
      $this->addColumn('user', 'tariff_unit', $this->string(400)->defaultValue('{}'));
      $this->addColumn('user', 'tariff_end_date', $this->integer()->defaultValue(0));
      $this->addColumn('user', 'tariff_id', $this->integer()->defaultValue(0));
    }

    public function down()
    {
      echo "m170215_134821_add_coumn_table_user cannot be reverted.\n";
      $this->dropColumn('user', 'credits');
      $this->dropColumn('user', 'tariff_unit');
      $this->dropColumn('user', 'tariff_end_date');
      $this->dropColumn('user', 'tariff_id');
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
