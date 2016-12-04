<?php

use yii\db\Migration;

class m161203_143343_add_moderation_colum_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'moderate', $this->integer(1)->defaultValue(0));
    }

    public function down()
    {
        echo "m161203_143343_add_moderation_colum_to_user cannot be reverted.\n";
        $this->dropColumn('user', 'moderate');
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
