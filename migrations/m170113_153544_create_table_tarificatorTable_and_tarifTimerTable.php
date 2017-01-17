<?php

use yii\db\Migration;
use yii\db\Schema;

class m170113_153544_create_table_tarificatorTable_and_tarifTimerTable extends Migration
{
    public function up()
    {
        $this->createTable('tarificatorTable', [
            'id' => $this->primaryKey(),
            'code'         => Schema::TYPE_INTEGER . ' NOT NULL',
            'name'         => Schema::TYPE_STRING . '(32) NOT NULL',
            'price'         => Schema::TYPE_DOUBLE . ' NOT NULL',
            'description'         => Schema::TYPE_TEXT . ' NOT NULL',
            'includeData'         => Schema::TYPE_TEXT . ' NOT NULL',
        ]);
        $this->createTable('tarifTimerTable', [
            'id' => $this->primaryKey(),
            'name'         => Schema::TYPE_STRING . '(32) NOT NULL',
            'price'         => Schema::TYPE_DOUBLE . ' NOT NULL',
            'timer'         => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    public function down()
    {
        echo "Drop tables.....\n";
        $this->dropTable('tarificatorTable');
        $this->dropTable('tarifTimerTable');
        return true;
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
