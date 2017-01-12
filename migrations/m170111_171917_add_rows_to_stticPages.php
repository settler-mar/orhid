<?php

use yii\db\Migration;
use yii\db\Schema;

class m170111_171917_add_rows_to_stticPages extends Migration
{
    public function up()
    {
        $this->alterColumn('static_pages','name',Schema::TYPE_STRING . '(50)');
        $this->alterColumn('static_pages','meta_title',Schema::TYPE_STRING . '(50)');
        $this->alterColumn('static_pages','keywords',Schema::TYPE_STRING . '(200)');
        $this->alterColumn('static_pages','description',Schema::TYPE_STRING . '(500)');
        $this->alterColumn('static_pages','text',Schema::TYPE_STRING);
        $this->batchInsert('static_pages', ['ID', 'name', 'language'], [
            ['1','Main page', 0],
            ['2','Main page', 1],
            ['3','Top', 0],
            ['4','Top', 1],
            ['5','Shop', 0],
            ['6','Shop', 1],
            ['7','about', 0],
            ['8','about', 1],
            ['9','legends', 0],
            ['10','legends', 1]]);
    }

    public function down()
    {
        echo "m170111_171917_add_rows_to_stticPages cannot be reverted.\n";

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
