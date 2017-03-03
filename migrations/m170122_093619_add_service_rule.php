<?php

use yii\db\Migration;

class m170122_093619_add_service_rule extends Migration
{
    public function up()
    {
      $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
        ['serviceCommands', 2, 'Service menu', NULL, time(), time()],
      ]);
      //Предустановленные значения таблицы разрешений auth_item_child
      $this->batchInsert('auth_item_child', ['parent', 'child'], [
        ['administrator', 'serviceCommands'],
      ]);
    }

    public function down()
    {
        echo "m170122_093619_add_service_rule cannot be reverted.\n";

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
