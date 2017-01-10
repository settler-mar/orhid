<?php

use yii\db\Migration;

/**
 * Handles adding 4 to table `staticpages`.
 */
class m170110_213314_add_4_columns_to_staticPages_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('static_pages', 'title', $this->string(50)->notNull());
        $this->addColumn('static_pages', 'meta_title', $this->string(50)->notNull());
        $this->addColumn('static_pages', 'keywords', $this->string(200)->notNull());
        $this->addColumn('static_pages', 'description', $this->string(500)->notNull());
        $this->addColumn('static_pages', 'index', $this->boolean()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('static_pages', 'title');
        $this->dropColumn('static_pages', 'meta_title');
        $this->dropColumn('static_pages', 'keywords');
        $this->dropColumn('static_pages', 'description');
        $this->dropColumn('static_pages', 'index');
    }
}
