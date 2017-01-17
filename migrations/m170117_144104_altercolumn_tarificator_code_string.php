<?php

use yii\db\Migration;
use yii\db\Schema;

class m170117_144104_altercolumn_tarificator_code_string extends Migration
{
    public function up()
    {
        $this->alterColumn('tarificatorTable','code',Schema::TYPE_STRING . '(32) NOT NULL');
        $this->alterColumn('tarifTimerTable','code',Schema::TYPE_STRING . '(32) NOT NULL');
    }

    public function down()
    {
        echo "m170117_144104_altercolumn_tarificator_code_string cannot be reverted.\n";

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
