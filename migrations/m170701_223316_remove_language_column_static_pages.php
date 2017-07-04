<?php

use yii\db\Migration;

class m170701_223316_remove_language_column_static_pages extends Migration
{
    public function up()
    {
      $this->dropColumn('static_pages', 'language');
    }

    public function down()
    {
      $this->addColumn('static_pages', 'language', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170701_223316_remove_language_column_static_pages cannot be reverted.\n";

        return false;
    }
    */
}
