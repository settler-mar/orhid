<?php

use yii\db\Migration;

class m170118_223338_add_rules_for_tarificator extends Migration
{
    public function up()
    {
        $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            ['tariffCreate', 2, 'Create tariffs', NULL, time(), time()],
            ['tarificatorCreate', 2, 'Create tarificator row', NULL, time(), time()],
            ['tariffUpdate', 2, 'Update tariffs', NULL, time(), time()],
            ['tarificatorUpdate', 2, 'Update tarificator', NULL, time(), time()],
            ['tariffView', 2, 'View tariffs', NULL, time(), time()],
            ['tarificatorView', 2, 'View tarificator', NULL, time(), time()],
        ]);
        //Предустановленные значения таблицы разрешений auth_item_child
        $this->batchInsert('auth_item_child', ['parent', 'child'], [
            ['administrator', 'tariffCreate'],
            ['administrator', 'tarificatorCreate'],
            ['administrator', 'tariffUpdate'],
            ['administrator', 'tarificatorUpdate'],
            ['administrator', 'tariffView'],
            ['administrator', 'tarificatorView'],
         ]);
    }

    public function down()
    {
        echo "m170118_223338_add_rules_for_tarificator cannot be reverted.\n";

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
