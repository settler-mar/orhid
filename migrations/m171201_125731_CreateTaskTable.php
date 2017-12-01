<?php

use yii\db\Migration;

class m171201_125731_CreateTaskTable extends Migration
{
  public function safeUp()
  {
    $this->createTable('task', [
      'id' => $this->primaryKey(),
      'user_id' => $this->integer()->notNull(),
      'task_id' => $this->integer()->notNull(),
      'date_todo' => $this->integer()->notNull(),
      'created_at' => $this->integer()->notNull(),
      'params' => $this->string()->null(),
    ]);

    $this->addColumn('payments', 'comment', $this->string()->null());
    $this->addColumn('user', 'pays_video', $this->string()->null());
  }

  public function safeDown()
  {
    echo "m171201_125731_CreateTaskTable cannot be reverted.\n";
    $this->dropTable('task');

    $this->dropColumn('payments', 'comment');
    $this->dropColumn('user', 'pays_video');

  }

}
