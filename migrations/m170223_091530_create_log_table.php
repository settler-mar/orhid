<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m170223_091530_create_log_table extends Migration
{
     /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'action' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'admin_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('log');
    }
}
