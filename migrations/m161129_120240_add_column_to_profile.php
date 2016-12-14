<?php

use yii\db\Migration;

class m161129_120240_add_column_to_profile extends Migration
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function up()
    {
        $this->addColumn('user', 'phone', $this->string());

        $this->addColumn('profile', 'passport', $this->integer());
        $this->addColumn('profile', 'birthday', $this->integer());
        $this->addColumn('profile', 'weight', $this->integer());
        $this->addColumn('profile', 'height', $this->integer());
        $this->addColumn('profile', 'eyes', $this->integer());
        $this->addColumn('profile', 'heir', $this->integer());
        $this->addColumn('profile', 'education', $this->integer());
        $this->addColumn('profile', 'religion', $this->integer());
        $this->addColumn('profile', 'marital_status', $this->integer());
        $this->addColumn('profile', 'children_count', $this->integer());
        $this->addColumn('profile', 'lang_proficiency', $this->integer());
        $this->addColumn('profile', 'smoking', $this->integer());
        $this->addColumn('profile', 'looking_age_from', $this->integer()->defaultValue(18));
        $this->addColumn('profile', 'looking_age_to', $this->integer()->defaultValue(60));
        $this->addColumn('profile', 'intro_age_from', $this->integer()->defaultValue(18));
        $this->addColumn('profile', 'intro_age_to', $this->integer()->defaultValue(60));
        $this->addColumn('profile', 'moderated', $this->integer()->defaultValue(0));


        $this->addColumn('profile', 'occupation', $this->string());//текст
        $this->addColumn('profile', 'lang_name', $this->string());//текст
        $this->addColumn('profile', 'address', $this->string());//текст
        $this->addColumn('profile', 'about', $this->string());//текст
        $this->addColumn('profile', 'ideal_relationship', $this->string());//текст
        $this->addColumn('profile', 'passport_img_1', $this->string());//текст
        $this->addColumn('profile', 'passport_img_2', $this->string());//текст
        $this->addColumn('profile', 'passport_img_3', $this->string());//текст
        $this->addColumn('profile', 'photos', $this->string());//текст
        $this->addColumn('profile', 'video', $this->string());//текст
        $this->addColumn('profile', 'video_about', $this->string());//текст

    }

    public function down()
    {
        echo "m161129_120240_add_Column_to_profile cannot be reverted.\n";
        $this->dropColumn('user', 'phone');

        $this->dropColumn('profile', 'passport');
        $this->dropColumn('profile', 'birthday');
        $this->dropColumn('profile', 'weight');
        $this->dropColumn('profile', 'height');
        $this->dropColumn('profile', 'eyes');
        $this->dropColumn('profile', 'heir');
        $this->dropColumn('profile', 'education');
        $this->dropColumn('profile', 'religion');
        $this->dropColumn('profile', 'marital_status');
        $this->dropColumn('profile', 'children_count');
        $this->dropColumn('profile', 'lang_proficiency');
        $this->dropColumn('profile', 'smoking');
        $this->dropColumn('profile', 'looking_age_from');
        $this->dropColumn('profile', 'looking_age_to');
        $this->dropColumn('profile', 'intro_age_from');
        $this->dropColumn('profile', 'intro_age_to');
        $this->dropColumn('profile', 'moderated');


        $this->dropColumn('profile', 'occupation');
        $this->dropColumn('profile', 'lang_name');
        $this->dropColumn('profile', 'address');
        $this->dropColumn('profile', 'about');
        $this->dropColumn('profile', 'ideal_relationship');
        $this->dropColumn('profile', 'passport_img_1');
        $this->dropColumn('profile', 'passport_img_2');
        $this->dropColumn('profile', 'passport_img_3');
        $this->dropColumn('profile', 'photos');
        $this->dropColumn('profile', 'video');
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
