<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `orhid_legends`.
 */
class m161223_224923_create_orhid_legends_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('orhid_legends', [
            'id'             => Schema::TYPE_PK,
            'title'          => Schema::TYPE_STRING . '(64) NOT NULL',
            'text'           => Schema::TYPE_STRING . ' NOT NULL',
            'image'          => Schema::TYPE_STRING . '(128) NOT NULL',
            'language'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'state'          => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('orhid_legends');
    }
}
