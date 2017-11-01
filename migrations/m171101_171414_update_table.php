<?php

use yii\db\Migration;

class m171101_171414_update_table extends Migration
{
    public function safeUp()
    {
      $this->dropColumn('orhid_legends', 'language');
      $this->alterColumn('orhid_legends','image',$this->string()->null());
    }

    public function safeDown()
    {
        echo "m171101_171414_update_table cannot be reverted.\n";
      $this->addColumn('orhid_legends', 'language', $this->string());
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171101_171414_update_table cannot be reverted.\n";

        return false;
    }
    */
}
