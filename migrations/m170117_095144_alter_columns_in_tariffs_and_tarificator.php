<?php

use yii\db\Migration;
use yii\db\Schema;

class m170117_095144_alter_columns_in_tariffs_and_tarificator extends Migration
{
    public function up()
    {
        $this->addColumn('tarificatorTable','timer',Schema::TYPE_INTEGER . ' NOT NULL');
        $this->dropColumn('tarifTimerTable','name');
        $this->dropColumn('tarifTimerTable','timer');
        $this->addColumn('tarifTimerTable','code',Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn('tarifTimerTable','description',Schema::TYPE_TEXT . ' NOT NULL');
    }

    public function down()
    {
        echo "m170117_095144_alter_columns_in_tariffs_and_tarificator cannot be reverted.\n";

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
