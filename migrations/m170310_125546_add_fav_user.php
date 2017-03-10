<?php

use yii\db\Migration;

class m170310_125546_add_fav_user extends Migration
{
    public function up()
    {
      $this->addColumn('user', 'favorites', $this->string()->defaultValue(""));
    }

    public function down()
    {
        echo "m170310_125546_add_fav_user cannot be reverted.\n";
      $this->dropColumn('user', 'favorites');
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
