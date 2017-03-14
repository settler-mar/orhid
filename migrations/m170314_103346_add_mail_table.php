<?php

use yii\db\Migration;

class m170314_103346_add_mail_table extends Migration
{
    public function up()
    {
      $this->createTable('mail', [
        'id' => $this->primaryKey(),
        'user_from' => $this->integer(),
        'user_to' => $this->integer(),
        'is_read' => $this->integer(1)->defaultValue(0),
        'message' => $this->text()->notNull(),
        'created_at' => $this->integer()->notNull()
      ]);
    }

    public function down()
    {
      echo "m170314_103346_add_mail_table cannot be reverted.\n";
      $this->dropTable('mail');
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
