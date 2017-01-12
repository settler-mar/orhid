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
        $this->alterColumn('static_pages','text',Schema::TYPE_TEXT);
        $this->alterColumn('static_pages','index',$this->boolean());
        $this->batchInsert('static_pages', ['ID', 'name', 'title', 'language'], [
            ['1','Main page','Main page', 0],
            ['2','Main page','Main page', 1],
            ['3','Top','Top', 0],
            ['4','Top','Top', 1],
            ['5','Shop','Shop', 0],
            ['6','Shop','Shop', 1],
            ['7','About','About', 0],
            ['8','About','About', 1],
            ['9','Legends','Legends', 0],
            ['10','Legends','Legends', 1],
            ['11','Mens list','Mens list', 0],
            ['12','Mens list','Mens list', 1],
            ['13','Competition','Competition', 0],
            ['14','Competition','Competition', 1],
            ['15','Online help','Online help', 0],
            ['16','Online help','Online help', 1],
          ]);
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
