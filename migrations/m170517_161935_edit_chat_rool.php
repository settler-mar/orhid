<?php

use yii\db\Migration;
use yii\db\Schema;
class m170517_161935_edit_chat_rool extends Migration
{
    public function safeUp()
    {
      $this->alterColumn('chat','message',Schema::TYPE_TEXT . ' CHARACTER SET utf8 NULL DEFAULT NULL');
    }

    public function safeDown()
    {
        echo "m170517_161935_edit_chat_rool cannot be reverted.\n";
      $this->alterColumn('chat','message',Schema::TYPE_STRING . '(255) CHARACTER SET utf8 NULL DEFAULT NULL');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170517_161935_edit_chat_rool cannot be reverted.\n";

        return false;
    }
    */
}
