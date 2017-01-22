<?php

use yii\db\Migration;

class m170118_132432_delete_code_column_tarificator extends Migration
{
    public function up()
    {
        $this->dropColumn('tarificatorTable','code');
    }

    public function down()
    {
        echo "m170118_132432_delete_code_column_tarificator cannot be reverted.\n";

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
