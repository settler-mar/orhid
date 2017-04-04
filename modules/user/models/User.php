<?php

namespace app\modules\user\models;

use app\models\LbCity;
use app\models\LbCountry;
use app\modules\chat\models\Chat;
use johnitvn\rbacplus\models\AssignmentSearch;
use Yii;
use app\modules\user\models\User;
use yii\rbac\Assignment;
use yii\web\IdentityInterface;
use karpoff\icrop\CropImageUploadBehavior;
use JBZoo\Image\Image;
use \yii\db\ActiveRecord;
use \yii\db\Query;
use app\modules\tariff\models\Tariff;

class User extends ActiveRecord  implements IdentityInterface
{

    public $userDir;
    public $password;

    // Статусы пользователя
    const STATUS_BLOCKED = 0;   // заблокирован
    const STATUS_ACTIVE = 1;    // активен
    const STATUS_WAIT = 2;      // ожидает подтверждения

    const MAX_ONLINE_TIME = 10*60;//Время после последнего запроса которое считается что пользователь онлайн (в секундах)

    // Время действия токенов
    const EXPIRE = 3600;
    public $toAdmin=false;

    /** @var string Default username regexp */
    public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@]+$/';
    public $captcha;    // Капча
    public $roles ;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function canIdo($code){
        $arr = json_decode($this->tariff_unit, true);
        if  (($arr[$code]!=null)&&($arr[$code]!='0')) return 1;
        else {
            $tariffs = Tariff::find()->where(['code' => $code])->select(['price'])->one();
            if ($tariffs){
                if ($tariffs->price > $this->credits ) return 0;
                else return 2;
            }
            else return 0;
        }
        return 0;
    }

    function behaviors()
    {
        return [
            [
                'class' => CropImageUploadBehavior::className(),
                'attribute' => 'photo',
                'scenarios' => ['insert', 'update'],
                'path' => '@webroot',
                'url' => '@web',
                'ratio' => 230/285,
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
            [['last_name', 'first_name', 'phone'], 'required'],
            [['email', 'last_name', 'first_name', 'password_hash','favorites'], 'string', 'max' => 100],
            [['tariff_unit'], 'string', 'max' => 400],
            [['username'], 'string', 'max' => 25],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6, 'max' => 61],
            [['sex', 'city', 'country', 'moderate', 'status','credits','tariff_end_date','tariff_id'], 'integer'],
            ['photo', 'file', 'extensions' => 'jpeg', 'on' => ['insert']],
            [['photo'], 'image',
                'minHeight' => 500,
                'maxSize'=>3*1024*1024,
                'skipOnEmpty' => true
            ],
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
            'password' => 'Password',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'city' => 'City',
            'country' => 'Country',
            'sex' => 'Sex',
            'captcha' => ' Captcha',
            'photo' => 'Photo',
            'phone' => 'Phone',
            'password_hash' => 'Хеш пароля',
            'moderate' => 'Moderation',
            'ip' => 'Last IP',
            'fullName' => 'Full Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return (strlen($this->photo)>5?$this->photo:'/img/not-ava.jpg');
    }
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
    public function getCity()
    {
        return $this->hasOne(LbCity::className(), ['id' => 'city']);
    }
    public function getCity_()
    {
        return $this->hasOne(LbCity::className(), ['id' => 'city']);
    }
    public function getCountry()
    {
        return $this->hasOne(LbCountry::className(), ['id' => 'country']);
    }
    public function getCountry_()
    {
        return $this->hasOne(LbCountry::className(), ['id' => 'country']);
    }
    public function getRole()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    //public function getChatMsgIn(){
      //return  $this->hasMany(Chat::className(), ['user_to' => 'id']);
      //return $this->hasMany(Chat::className(), ['user_to' => 'id'])->count();
        //->viaTable('{{%Chat}} b', ['b.user_to'=>'id']);
    //}
    /*public function getChatMsgOut(){
      return  $this->hasMany(Chat::className(), ['user_from' => 'id']);
    }*/
    /*public function getMsgcnt(){
        return $this->getMessage()->count();
    }*/
    /*public function getChatMessage(){
      return Chat::find()
        ->where(['user_to' => $this->id])
        ->orWhere(['user_from' => $this->id]);
    }*/

    /*public function getMsgnew(){
        return $this->getMessage()
          ->where(['user_to'=>$this->id,'is_read'=>0])
          ->count();
    }*/
    public function getChatInbox(){
      //$my_id=$this->id;
      //,'user_from'=>$my_id,'created_at>'.(time()-30*60*60*24)
      return $this->hasMany(Chat::className(), ['user_to'=>'id']);
    }
    /* Геттер для полного имени человека */
    public function getFullName() {
        return $this->last_name . ' ' . $this->first_name;
    }

    /* Геттер для полного ника человека */
    public function getFullNick() {
      $proile=$this->getProfile()->one();
      if($proile->birthday>0){
        return $this->last_name . ' ' . date('Y',$proile->birthday);
      }else{
        return $this->last_name . ' ' . $this->first_name;
      }
    }

    public function getSexArray()
    {
        return array(0 => 'Men', 1 => 'Female');
    }

    public function isManager(){
        return ($this->getRoleOfUser($this->id,'administrator')||$this->getRoleOfUser($this->id,'moderator'));
    }

    public function getRoleOfUser($id,$roleName)
    {
        if (!isset($this->roles) || !is_array($this->roles)) {
            $roles = (new Query)
                ->select('item_name')
                ->from('auth_assignment')
                ->where(['user_id' => $id])
                ->all();
            $this->roles=array();
            if($roles){
                foreach ($roles as $role){
                    $this->roles[] = $role['item_name'];
                }
            }
        }
        return in_array($roleName,$this->roles);
    }
    /**
     * Поиск пользователя по Id
     * @param int|string $id - ID
     * @return null|static
     */
    public static function findIdentity($id)
    {
        $user = static::findOne(['id' => $id]);
        if ($user) {
            $user->userDir = $user->getUserPath($id);
        };
        return $user;
    }

    public static function findRandom($isOnline=false)
    {

      $where=array();
      if(Yii::$app->user->isGuest){
        $where['user.sex'] = 1;
      }else {
        $where['user.sex'] = (1 - Yii::$app->user->identity->sex);
      }
      $where['moderate']=1;

      //ddd($where);
      $user=User::find()->where($where);
      if($isOnline){
        $user=$user->andFilterWhere(['and',['>','last_online',time()-User::MAX_ONLINE_TIME]]);
        $cnt=$user->count();
        if($cnt<1){
          $user=User::find()->where($where);
          $cnt=$user->count();
        }
      }else{
        $cnt=$user->count();
      }

      $cnt=rand(0,$cnt-1);
      $user=$user->offset($cnt);
      $user=$user->limit(1);
      $user=$user->one();

      return $user;
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
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генераия Хеша пароля
     * @param $password - пароль
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
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
        $timestamp = (int)end($parts);
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

    public function beforeSave($insert)
    {
        $oldValue = $this->getOldAttributes();
        //проверяем существовние пользователя
        if ($oldValue && isset($oldValue['email']) && $oldValue['email'] != $this->email){
        if ($this->findByEmail($this->email)) {
            $this->addError('email', 'Email already exists');
            return false;
        }
    }

        return true;
    }
    /**
     * @param bool $insert
     * @param array $changedAttributes
     * Сохраняем изображения после сохранения
     * данных пользователя
     */
    public function afterSave($insert, $changedAttributes)
    {
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
            'login_at' => date('Y-m-d H:i:s'),
            'last_online'=> time(),
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
            $path = $this->getUserPath($this->id);// Путь для сохранения аватаров
            $oldImage = $this->photo;

            $name = time() . '-' . $this->id; // Название файла
            $exch = explode('.', $photo->name);
            $exch = $exch[count($exch) - 1];
            $name .= '.' . $exch;
            $this->photo = $path . $name;   // Путь файла и название
            if (!file_exists($path)) {
                mkdir($path, 0777, true);   // Создаем директорию при отсутствии
            }

            $request = Yii::$app->request;
            $post = $request->post();

            $class = $this::className();
            $class = str_replace('\\', '/', $class);
            $class = explode('/', $class);
            $class = $class[count($class) - 1];
            $cropParam = array();
            if (isset($post[$class])) {
                $cropParam = explode('-', $post[$class]['photo']);
            }
            if (count($cropParam) != 4) {
                $cropParam = array(0, 0, 100, 100);
            }

            $img = (new Image($photo->tempName));
            $imgWidth = $img->getWidth();
            $imgHeight = $img->getHeight();


            $cropParam[0] = (int)($cropParam[0] * $imgWidth / 100);
            $cropParam[1] = (int)($cropParam[1] * $imgHeight / 100);
            $cropParam[2] = (int)($cropParam[2] * $imgWidth / 100);
            $cropParam[3] = (int)($cropParam[3] * $imgHeight / 100);

            $img->crop($cropParam[0], $cropParam[1], $cropParam[2], $cropParam[3])
                ->fitToWidth(500)
                ->saveAs($this->photo);


            if ($img) {
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
                    $name .= " (" . $m->id . ")";
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
    public function getUserPath($id) {
        $path = 'user_file/' . floor($id / 100) . '/' . ($id % 100) . '/';
        return $path;
    }

    public function rmdir($id) {
        //чистим папку файла
        $path = $this->getUserPath($id);
        $files = glob($path."*");
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        if(file_exists($path))rmdir($path);
        return true;
    }
}
