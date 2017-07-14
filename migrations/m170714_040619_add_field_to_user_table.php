<?php

use yii\db\Migration;

class m170714_040619_add_field_to_user_table extends Migration
{
    public function up()
    {
      $this->addColumn('user', 'top', $this->integer()->defaultValue(0));
    }

    public function down()
    {
      $this->dropColumn('user', 'top');
      return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170714_040619_add_field_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
