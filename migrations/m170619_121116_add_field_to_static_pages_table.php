<?php

use yii\db\Migration;

class m170619_121116_add_field_to_static_pages_table extends Migration
{
  public function up()
  {
    $this->addColumn('static_pages', 'url', $this->string()->defaultValue(""));
  }

  public function down()
  {
    $this->dropColumn('static_pages', 'url');
    return true;
  }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170619_121116_add_field_to_static_pages_table cannot be reverted.\n";

        return false;
    }
    */
}
