<?php

use yii\db\Migration;

class m180312_154043_sp_ladies extends Migration
{
    public function safeUp()
    {
      $sp=\app\modules\staticPages\models\StaticPages::findOne(['id'=>12]);
      $sp->name="Ladies";
      $sp->title="ladies";
      $sp->url='ladies';
      $sp->index=0;
      $sp->save();

      \app\modules\staticPages\models\StaticPages::deleteAll(['id'=>[2,4,6,7,14,16]]);
    }

    public function safeDown()
    {
        echo "m180312_154043_sp_ladies cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180312_154043_sp_ladies cannot be reverted.\n";

        return false;
    }
    */
}
