<?php

use yii\db\Migration;

class m161222_121529_create_chat extends Migration
{
    public function up()
    {
        $this->createTable('chat', [
            'id' => $this->primaryKey(),
            'user_from' => $this->integer(),
            'user_to' => $this->integer(),
            'is_read' => $this->integer(1)->defaultValue(0),
            'message' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);
    }

    public function down()
    {
        echo "m161222_121529_messager_and_chat cannot be reverted.\n";
        $this->dropTable('chat');
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
