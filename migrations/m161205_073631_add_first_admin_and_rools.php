<?php

use yii\db\Migration;
use app\modules\user\models\User;
use johnitvn\rbacplus\models\AuthItem;


class m161205_073631_add_first_admin_and_rools extends Migration
{

    //Администратор по умолчанию
    const ADMIN_FIRST_NAME = 'Имя';
    const ADMIN_LAST_NAME = 'Фамилия';
    const ADMIN_USERNAME = 'Admin';
    const ADMIN_EMAIL = 'admin@example.ru';
    const ADMIN_PASSWORD = 'admin';

    public function up()
    {

        //Предустановленные значения таблицы пользователей user
        $this->batchInsert('user', [
            'id',
            'first_name',
            'last_name',
            'email',
            'username',
            'auth_key',
            'password_hash',
            'status',
            'created_at',
            'updated_at',
            'moderate',
            'sex',
            'country',
            'city'
        ], [
            [
                1,
                self::ADMIN_FIRST_NAME,
                self::ADMIN_LAST_NAME,
                self::ADMIN_EMAIL,
                self::ADMIN_USERNAME,
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generatePasswordHash(self::ADMIN_PASSWORD),
                User::STATUS_ACTIVE,
                time(),
                time(),
                1,
                0,
                222,
                698740
            ]
        ]);



        //Предустановленные значения таблицы ролей и допусков auth_item
        $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            ['administrator', 1, 'Администратор', NULL, time(), time()],
            ['moderator', 1, 'Модератор', NULL, time(), time()],
            ['rbac', 2, 'Роли пользователей', NULL, time(), time()],
            ['userManager', 2, 'Редактирование пользователя', NULL, time(), time()],

        ]);

        //Предустановленные значения таблицы разрешений auth_item_child
        $this->batchInsert('auth_item_child', ['parent', 'child'], [
            ['moderator', 'userManager'],
            ['administrator', 'moderator'],
            ['administrator', 'rbac'],
        ]);

        //Предустановленные значения таблицы связи ролей auth_assignment
        $this->batchInsert('auth_assignment', ['item_name', 'user_id', 'created_at'], [
            ['administrator', 1, time()]
        ]);

        $this->alterColumn ( 'auth_assignment', 'user_id', $this->integer(11));
        $this->addForeignKey('auth_assignment_user_id', 'auth_assignment', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        echo "m161205_073631_add_first_admin_and_rools cannot be reverted.\n";

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
