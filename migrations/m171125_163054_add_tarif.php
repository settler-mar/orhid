<?php

use yii\db\Migration;

class m171125_163054_add_tarif extends Migration
{
    public function safeUp()
    {
      $this->truncateTable('tarifTimerTable');
      $this->truncateTable('tarificatorTable');


      $this->batchInsert('tarifTimerTable', [
        'price',
        'code',
        'description',
      ], [
        [
          4,
          'video_intro',
          'Introduction Video'
        ],
        [
          7,
          'mail_intro_out',
          'Outcoming Introductions'
        ],
        [
          7,
          'mail_out',
          'Outcoming Follow-ups'
        ],
        [
          7,
          'chat_text',
          'Live Chat'
        ],
      ]);

      $this->dropColumn('tarificatorTable', 'color');

      $this->batchInsert('tarificatorTable', [
        'id',
        'name',
        'price',
        'includeData',
        'description',
        'timer',
        'credits',
      ], [
        [
          1,
          'Bronze',
          0,
          '{"mail_intro_out":"0","mail_out":"0"}',
          'test',
          30,
          0
        ],
        [
          2,
          'Silver',
          10,
          '{"mail_intro_out":"10","mail_out":"0"}',
          'test',
          30,
          0
        ],
        [
          3,
          'Gold',
          30,
          '{"mail_intro_out":"20","mail_out":"15"}',
          'test',
          30,
          0
        ],
        [
          4,
          'Platinum',
          40,
          '{"mail_intro_out":"30","mail_out":"20"}',
          'test',
          30,
          0
        ],
        [
          5,
          'Diamond',
          450,
          '{"mail_intro_out":"0","mail_out":"100"}',
          'test',
          30,
          600
        ],
        [
          6,
          '40 credits',
          30,
          '{}',
          'test',
          0,
          40
        ],
        [
          7,
          '100 credits',
          70,
          '{}',
          'test',
          0,
          100
        ],
        [
          8,
          '200 credits',
          120,
          '{}',
          'test',
          0,
          200
        ],
        [
          9,
          '300 credits',
          170,
          '{}',
          'test',
          0,
          300
        ],
        [
          10,
          '500 credits',
          250,
          '{}',
          'test',
          0,
          500
        ],
        [
          11,
          '1000 credits',
          470,
          '{}',
          'test',
          0,
          1000
        ],
      ]);
    }

    public function safeDown()
    {
        echo "m171125_163054_add_tarif cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171125_163054_add_tarif cannot be reverted.\n";

        return false;
    }
    */
}
