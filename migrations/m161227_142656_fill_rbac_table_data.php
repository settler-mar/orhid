<?php
use yii\db\Migration;
use yii\rbac\DbManager;
class m161227_142656_fill_rbac_table_data extends Migration
{
    public function up()
    {

            $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            ['blogCreate', 2, 'Create Blog', NULL, time(), time()],
            ['legendCreate', 2, 'Create Legend', NULL, time(), time()],
            ['staticPagesCreate', 2, 'Create static pages', NULL, time(), time()],
            ['blogUpdate', 2, 'Update Blog', NULL, time(), time()],
            ['legendUpdate', 2, 'Update Legend', NULL, time(), time()],
            ['staticPagesUpdate', 2, 'Update static pages', NULL, time(), time()],
            ['blogDelete', 2, 'Delete Blog', NULL, time(), time()],
            ['legendDelete', 2, 'Delete Legend', NULL, time(), time()],
            ['staticPagesDelete', 2, 'Delete static page', NULL, time(), time()],
            ['staticPagesAccess', 2, 'Access to static page table', NULL, time(), time()],
        ]);
        //Предустановленные значения таблицы разрешений auth_item_child
        $this->batchInsert('auth_item_child', ['parent', 'child'], [
            ['administrator', 'blogCreate'],
            ['administrator', 'legendCreate'],
            ['administrator', 'staticPagesCreate'],
            ['administrator', 'blogUpdate'],
            ['administrator', 'legendUpdate'],
            ['administrator', 'staticPagesUpdate'],
            ['administrator', 'blogDelete'],
            ['administrator', 'legendDelete'],
            ['administrator', 'staticPagesDelete'],
            ['administrator', 'staticPagesAccess'],
        ]);
        //Предустановленные значения таблицы связи ролей auth_assignment
    }
    public function down()
    {
        echo "Hello, user";
        return true;
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