<?php

use yii\db\Migration;

class m170626_221006_add_new_field_to_orhid_legends_table extends Migration
{
    public function up()
    {
      $this->addColumn('orhid_legends', 'video', $this->string());
      $this->addColumn('orhid_legends', 'cover', $this->string());
    }

    public function down()
    {
      $this->dropColumn('orhid_legends', 'video');
      $this->dropColumn('orhid_legends', 'cover');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170626_221006_add_new_field_to_orhid_legends_table cannot be reverted.\n";

        return false;
    }
    */
}
