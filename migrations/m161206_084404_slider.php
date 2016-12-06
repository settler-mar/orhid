<?php

use yii\db\Migration;
use yii\db\Schema;

class m161206_084404_slider extends Migration
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function up()
    {
        $this->createTable('{{%slider_images}}', [
            'image_id'        => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'address'         => Schema::TYPE_STRING . '(60) NOT NULL',
            'text'         => Schema::TYPE_STRING . '(256) NOT NULL',
        ], $this->tableOptions);
    }

    public function down()
    {
        echo "m161206_084404_slider cannot be reverted.\n";
        $this->dropTable('{{%slider_images}}');

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
