<?php

use yii\db\Migration;
use yii\db\Schema;

class m161127_075653_add_new_field_to_user extends Migration
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username'             => Schema::TYPE_STRING . '(25) NOT NULL',
            'email'                => Schema::TYPE_STRING . '(255) NOT NULL',
            'first_name'           => Schema::TYPE_STRING . '(60) NOT NULL',
            'last_name'            => Schema::TYPE_STRING . '(60) NOT NULL',
            'city'                 => Schema::TYPE_INTEGER . ' NOT NULL',
            'country'              => Schema::TYPE_INTEGER . ' NOT NULL',
            'sex'                  => Schema::TYPE_INTEGER . ' NOT NULL',
            'status'               => Schema::TYPE_INTEGER . ' DEFAULT \'0\'',
            'role'                 => Schema::TYPE_INTEGER . ' DEFAULT \'0\'',
            'password_hash'        => Schema::TYPE_STRING . '(60)',
            'photo'                => Schema::TYPE_STRING . '(60)',
            'password_reset_token' => Schema::TYPE_STRING . '(60)',
            'email_confirm_token'  => Schema::TYPE_STRING . '(60)',
            'auth_key'             => Schema::TYPE_STRING . '(32)',
            'created_at'           => Schema::TYPE_DATETIME . ' NULL DEFAULT NULL',
            'updated_at'           => Schema::TYPE_DATETIME . ' NULL DEFAULT NULL',
            'login_at'             => Schema::TYPE_DATETIME . ' NULL DEFAULT NULL',
            'ip'                   => Schema::TYPE_STRING . '(20) NULL DEFAULT NULL',
        ], $this->tableOptions);
        $this->createIndex('user_unique_username', '{{%user}}', 'username', true);
        $this->createIndex('user_unique_email', '{{%user}}', 'email', true);
        $this->createIndex('user_recovery', '{{%user}}', 'id, password_reset_token', true);

        $this->createTable('{{%profile}}', [
            'user_id'        => Schema::TYPE_INTEGER . ' PRIMARY KEY',

        ], $this->tableOptions);
        $this->addForeignKey('fk_user_profile', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        echo "m161127_075653_add_new_field_to_user cannot be reverted.\n";
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
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
