<?php

use yii\db\Migration;

class m161212_125126_country_add_white_row extends Migration
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function up()
    {
        $this->addColumn('lb_country', 'in_white', $this->integer(1)->defaultValue(1));
    }

    public function down()
    {
        echo "m161212_125126_country_add_white_row cannot be reverted.\n";
        $this->dropColumn('lb_country', 'in_white');
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
