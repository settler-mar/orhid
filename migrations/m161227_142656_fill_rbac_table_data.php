<?php
use yii\db\Migration;
use yii\rbac\DbManager;
class m161227_142656_fill_rbac_table_data extends Migration
{
    public function up()
    {
        //Предустановленные значения таблицы ролей и допусков auth_item
        $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            ['createBlog', 2, 'Create Blog', NULL, time(), time()],
            ['createLegend', 2, 'Create Legend', NULL, time(), time()],
            ['updateBlog', 2, 'Update Blog', NULL, time(), time()],
            ['updateLegend', 2, 'Update Legend', NULL, time(), time()],
            ['deleteBlog', 2, 'Delete Blog', NULL, time(), time()],
            ['deleteLegend', 2, 'Delete Legend', NULL, time(), time()],
            ['staticPagesAccess', 2, 'Access to static pages table', NULL, time(), time()],
        ]);
        //Предустановленные значения таблицы разрешений auth_item_child
        $this->batchInsert('auth_item_child', ['parent', 'child'], [
            ['administrator', 'createBlog'],
            ['administrator', 'createLegend'],
            ['administrator', 'updateBlog'],
            ['administrator', 'updateLegend'],
            ['administrator', 'deleteBlog'],
            ['administrator', 'deleteLegend'],
            ['administrator', 'staticPagesAccess'],
        ]);
        //Предустановленные значения таблицы связи ролей auth_assignment
    }
    public function down()
    {
        echo "m161227_142656_fill_rbac_table_data cannot be reverted.\n";
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