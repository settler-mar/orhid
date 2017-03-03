<?php

use yii\db\Migration;

/**
 * Handles adding color to table `taritarificatortable`.
 */
class m170129_204920_add_color_column_to_taritarificatorTable_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('tarificatorTable', 'color', $this->string(8)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('tarificatorTable', 'color');
    }
}
