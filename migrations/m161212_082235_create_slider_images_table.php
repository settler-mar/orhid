<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Handles the creation of table `slider_images`.
 */
class m161212_082235_create_slider_images_table extends Migration
{
    /**
     * @inheritdoc
     */
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function up()
    {
        $this->dropTable('slider_images');
        $this->createTable('slider_images', [
            'image_id' => $this->primaryKey(),
            'address'         => Schema::TYPE_STRING . '(60) NOT NULL',
            'text'         => Schema::TYPE_STRING . '(256)',
            'gender'         => Schema::TYPE_STRING . '(10) NOT NULL',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('slider_images');
    }
}
