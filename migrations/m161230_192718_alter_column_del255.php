<?php

use yii\db\Migration;
use yii\db\Schema;

class m161230_192718_alter_column_del255 extends Migration
{
    public function up()
    {
        $this->alterColumn('orhid_legends','text',Schema::TYPE_TEXT . ' NOT NULL');
        $this->alterColumn('orhid_blog','text',Schema::TYPE_TEXT . ' NOT NULL');
        $this->alterColumn('static_pages','text',Schema::TYPE_TEXT . ' NOT NULL');
    }

    public function down()
    {
        echo "m161230_192718_alter_column_del255 cannot be reverted.\n";

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
