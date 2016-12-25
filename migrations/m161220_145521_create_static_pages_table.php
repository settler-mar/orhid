<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Handles the creation of table `static_pages`.
 */
class m161220_145521_create_static_pages_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('static_pages', [
            'id'             => Schema::TYPE_PK,
            'name'           => Schema::TYPE_STRING . '(32) NOT NULL',
            'text'           => Schema::TYPE_STRING . ' NOT NULL',
            'language'       => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('static_pages');
    }
}
