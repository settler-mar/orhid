<?php

use yii\db\Migration;

class m161212_134139_user_unkey_email extends Migration
{
    public function up()
    {
        // drops foreign key for table `post`
        $this->dropIndex(
            'user_unique_email',
            'user'
        );
    }

    public function down()
    {
        echo "m161212_134139_user_unkey_email cannot be reverted.\n";
        $this->createIndex('user_unique_email', '{{%user}}', 'email', true);
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
