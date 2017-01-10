<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `orhid_blog`.
 */
class m161223_225410_create_orhid_blog_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('orhid_blog', [
            'id'             => Schema::TYPE_PK,
            'title'          => Schema::TYPE_STRING . '(64) NOT NULL',
            'annotation'     => Schema::TYPE_STRING . '(256) NOT NULL',
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
        $this->dropTable('orhid_blog');
    }
}
