<?php

namespace app\modules\user\models;

use Yii;
use app\modules\user\models\User;
use karpoff\icrop\CropImageUploadBehavior;
use JBZoo\Image\Image;

class User extends \yii\db\ActiveRecord
{

    /** @var string Default username regexp */
    public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@]+$/';
    public $captcha;    // Капча

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    function behaviors()
    {
        return [
            [
                'class' => CropImageUploadBehavior::className(),
                'attribute' => 'photo',
                'scenarios' => ['insert', 'update'],
                'path' => '@webroot/upload/docs',
                'url' => '@web/upload/docs',
                'ratio' => 1,
                /*'crop_field' => 'photo_crop',
                'cropped_field' => 'photo_cropped',*/
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'last_name', 'first_name','captcha'], 'required'],
            [['password', 'email', 'last_name', 'first_name'], 'string', 'max' => 60],
            [['username'], 'string', 'max' => 25],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6, 'max' => 72, 'on' => ['register', 'create']],
            //['verificationCode', 'captcha'],
            [['sex','city','country'], 'integer'],
            ['captcha', 'captcha', 'captchaAction' => 'user/default/captcha'], // Проверка капчи
            ['photo', 'file', 'extensions' => 'jpeg', 'on' => ['insert', 'update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'city' => 'City',
            'country' => 'Country',
            'sex' => 'Sex',
            'captcha' => ' captcha'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    public function getSexArray(){
        return array( 0 => 'Men', 1 => 'Female');
    }

    /**
     * Поиск пользователя по Id
     * @param int|string $id - ID
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    /**
     * Поиск пользователя по Email
     * @param $email - электронная почта
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }
    /**
     * Поиск пользователя по Username
     * @param $username - электронная почта
     * @return null|static
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    /**
     * Ключ авторизации
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    /**
     * ID пользователя
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Проверка ключа авторизации
     * @param string $authKey - ключ авторизации
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    /**
     * Поиск по токену доступа (не поддерживается)
     * @param mixed $token - токен
     * @param null $type - тип
     * @throws NotSupportedException - Исключение "Не подерживается"
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(Yii::t('user', 'Поиск по токену не поддерживается.'));
    }
    /**
     * Проверка правильности пароля
     * @param $password - пароль
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    /**
     * Генераия Хеша пароля
     * @param $password - пароль
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Поиск по токену восстановления паролья
     * Работает и для неактивированных пользователей
     * @param $token - токен восстановления пароля
     * @return null|static
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token
        ]);
    }
    /**
     * Генерация случайного авторизационного ключа
     * для пользователя
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * Проверка токена восстановления пароля
     * согласно его давности, заданной константой EXPIRE
     * @param $token - токен восстановления пароля
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + self::EXPIRE >= time();
    }
    /**
     * Генерация случайного токена
     * восстановления пароля
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * Очищение токена восстановления пароля
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /**
     * Проверка токена подтверждения Email
     * @param $email_confirm_token - токен подтверждения электронной почты
     * @return null|static
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }
    /**
     * Генерация случайного токена
     * подтверждения электронной почты
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }
    /**
     * Очищение токена подтверждения почты
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }
    /**
     * @param bool $insert
     * @param array $changedAttributes
     * Сохраняем изображения после сохранения
     * данных пользователя
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveImage();
    }
    /**
     * Действия, выполняющиеся после авторизации.
     * Сохранение IP адреса и даты авторизации.
     *
     * Для активации текущего обновления необходимо
     * повесить текущую функцию на событие 'on afterLogin'
     * компонента user в конфигурационном файле.
     * @param $id - ID пользователя
     */
    public static function afterLogin($id)
    {
        self::getDb()->createCommand()->update(self::tableName(), [
            'ip' => $_SERVER["REMOTE_ADDR"],
            'login_at' => date('Y-m-d H:i:s')
        ], ['id' => $id])->execute();
    }
    /**
     * Сохранение изображения (аватара)
     * пользвоателя
     */
    public function saveImage()
    {
        $photo = \yii\web\UploadedFile::getInstance($this, 'photo');

        if ($photo) {
            $path=$this->getUserPath($this->id);// Путь для сохранения аватаров
            $oldImage=$this->photo;

            $name = time() . '-' . $this->id; // Название файла
            $exch = explode('.',$photo->name);
            $exch=$exch[count($exch)-1];
            $name .= '.' . $exch;
            $this->photo = $path . $name ;   // Путь файла и название
            if (!file_exists($path)) {
                mkdir($path, 0777, true);   // Создаем директорию при отсутствии
            }

            $request = Yii::$app->request;
            $post = $request->post();
            $cropParam=explode('-',$post['RegistrationForm']['photo']);
            if(count($cropParam)!=4) {
                $cropParam=array(0,0,100,100);
            }

            $img = (new Image($photo->tempName));
            $imgWidth = $img->getWidth();
            $imgHeight = $img->getHeight();


            $cropParam[0]=(int)($cropParam[0]*$imgWidth/100);
            $cropParam[1]=(int)($cropParam[1]*$imgHeight/100);
            $cropParam[2]=(int)($cropParam[2]*$imgWidth/100);
            $cropParam[3]=(int)($cropParam[3]*$imgHeight/100);

            $img->crop($cropParam[0], $cropParam[1], $cropParam[2], $cropParam[3])
                ->fitToWidth(500)
                ->saveAs($this->photo);


            if($img) {
                $this->removeImage($oldImage);   // удаляем старое изображение

                $this::getDb()
                    ->createCommand()
                    ->update($this->tableName(), ['photo' => $this->photo], ['id' => $this->id])
                    ->execute();
            }
        }
    }
    /**
     * Удаляем изображение при его наличии
     */
    public function removeImage($img)
    {
        if ($img) {
            // Если файл существует
            if (file_exists($img)) {
                unlink($img);
            }
        }
    }
    /**
     * Список всех пользователей
     * @param bool $show_id - показывать ID пользователя
     * @return array - [id => Имя Фамилия (ID)]
     */
    public static function getAll($show_id = false)
    {
        $users = [];
        $model = self::find()->all();
        if ($model) {
            foreach ($model as $m) {
                $name = ($m->last_name) ? $m->first_name . " " . $m->last_name : $m->first_name;
                if ($show_id) {
                    $name .= " (".$m->id.")";
                }
                $users[$m->id] = $name;
            }
        }
        return $users;
    }


    /**
     * Путь к папке пользователя
     * @id - ID пользователя
     * @return путь(string)
     */
    public function getUserPath($id){
        $path = 'user_file/'.floor($id/100).'/'.($id % 100).'/';
        return $path;
    }
}
