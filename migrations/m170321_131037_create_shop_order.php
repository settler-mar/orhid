<?php

use yii\db\Migration;

class m170321_131037_create_shop_order extends Migration
{
    public function up()
    {
      $this->createTable('shop_order', [
        'id' => $this->primaryKey(),
        'user_from' => $this->integer()->notNull(),
        'user_to' => $this->integer()->notNull(),
        'item_id' => $this->integer()->notNull(),
        'price' => $this->float()->notNull(),
        'admin' => $this->integer()->defaultValue(0),
        'status' => $this->integer()->defaultValue(0),
        'user_comment' => $this->string(1000)->defaultValue(""),
        'user_admin' => $this->string(1000)->defaultValue(""),
        'created_at' => $this->integer()->notNull(),
      ]);
    }

    public function down()
    {
      echo "m170321_131037_create_shop_order cannot be reverted.\n";
      $this->dropTable('shop_order');
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
