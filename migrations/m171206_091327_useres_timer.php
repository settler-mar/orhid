<?php

use yii\db\Migration;

class m171206_091327_useres_timer extends Migration
{
    public function safeUp()
    {
      $this->addColumn('user', 'last_pays', $this->string());
    }

    public function safeDown()
    {
        echo "m171206_091327_useres_timer cannot be reverted.\n";
      $this->dropColumn('user', 'last_pays');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171206_091327_useres_timer cannot be reverted.\n";

        return false;
    }
    */
}
