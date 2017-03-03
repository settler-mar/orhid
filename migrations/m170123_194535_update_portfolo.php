<?php

use yii\db\Migration;
use yii\db\Schema;

class m170123_194535_update_portfolo extends Migration
{
    public function up()
    {
      $this->alterColumn('profile','about',Schema::TYPE_STRING . '(1000) CHARACTER SET utf8 NULL DEFAULT NULL');
      $this->alterColumn('profile','ideal_relationship',Schema::TYPE_STRING . '(1000) CHARACTER SET utf8 NULL DEFAULT NULL');
    }

    public function down()
    {
        echo "m170123_194535_update_portfolo cannot be reverted.\n";
        $this->alterColumn('profile','about',Schema::TYPE_STRING . '(255) CHARACTER SET utf8 NULL DEFAULT NULL');
        $this->alterColumn('profile','ideal_relationship',Schema::TYPE_STRING . '(255) CHARACTER SET utf8 NULL DEFAULT NULL');
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
