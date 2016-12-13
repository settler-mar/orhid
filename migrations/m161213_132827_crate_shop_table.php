<?php

use yii\db\Migration;

class m161213_132827_crate_shop_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_store', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->unique()->notNull(),
            'description' => $this->text()->notNull(),
            'picture' => $this->string(100),
            'price' => $this->float()->notNull(),
            'comment' => $this->string(255),
            'active' => $this->boolean()->defaultValue(true),
            'created_at' => $this->datetime()->notNull(),
        ]);
    }

    public function down()
    {
        echo "m161213_132827_crate_shop_table cannot be reverted.\n";
        $this->dropTable('shop_store');
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
