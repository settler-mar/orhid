<?php

namespace app\modules\user\models;

use app\models\LbCity;
use app\models\LbCountry;
use app\modules\chat\models\Chat;
use app\modules\tarificator\models\TarificatorTable;
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

class User extends ActiveRecord implements IdentityInterface
{

  public $userDir;
  public $password;

  // Статусы пользователя
  const STATUS_BLOCKED = 0;   // заблокирован
  const STATUS_ACTIVE = 1;    // активен
  const STATUS_WAIT = 2;      // ожидает подтверждения

  const MAX_ONLINE_TIME = 10 * 60;//Время после последнего запроса которое считается что пользователь онлайн (в секундах)

  // Время действия токенов
  const EXPIRE = 3600;
  public $toAdmin = false;

  /** @var string Default username regexp */
  public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@]+$/';
  public $captcha;    // Капча
  public $roles;

  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return 'user';
  }

  public function canIdo($code, $write_off = false)
  {
    if ($this->sex == 1) return true; //если женщина то для нее нет тарификации
    $iCan = false;

    $arr = json_decode($this->tariff_unit, true);;
    if (in_array($code, array('chat_text'))) {
      if (strlen($this->last_pays) < 8) {
        $last_pays = [];
      } else {
        $last_pays = json_decode($this->last_pays, true);
      }

      $time = isset($last_pays[$code]) ? time() - $last_pays[$code] : 0;
      if ($time > 5 * 60) { //если прошло более 5 минут то считаем что время не уже закончилось и начался новый интервал
        $time = 0;
        $units = 0;
      } else {
        $units = $time / 60;
      }


      $last_pays[$code] = time();
      $this->last_pays = json_encode($last_pays);
    } else {
      $units = 1;
    }

    //Достаточно ли баланса на кредитных юнитах
    if (isset($arr[$code])) {
      if ($arr[$code] > $units) {
        //если на балансе достаточно то списываем
        $arr[$code] -= $units;
        $iCan = true;
      } else {
        $arr[$code] = 0;
      }
    }

    //проверяем достаточно ли денег на балансе
    if (!$iCan) {
      $price = Tariff::find()
          ->where(['code' => $code])
          ->asArray()
          ->one();
      $price = $price['price'] * $units;

      if ($price == 0 && isset($arr['credits'])) { //для случая когда таймер только начат или услуга бесплатно но только для положительного баланса
        $iCan = (($arr['credits'] && $arr['credits'] > 0) || $this->credits > 0);
      } else {

        //еслть ли пакетные кредиты
        if (isset($arr['credits']) && $arr['credits'] > 0) {
          $arr['credits'] -= $price;

          if ($arr['credits'] < 0) {
            //если не хватило тарифных кредитов то пробуем добрать из основных
            $this->credits += $arr['credits'];
            if ($this->credits > 0) {
              $iCan = true;
              $arr['credits'] = 0;
            }
          } else {
            $iCan = true;
          }
        } else {
          //в случаи отсутствия тарифных кредитов пробуем списать с баланса
          $this->credits -= $price;
          if ($this->credits > 0) {
            $iCan = true;
          }
        }
      }
    }

    if (!$write_off && $iCan) { //Если действие доступно и списывание раздрешено
      $this->tariff_unit = json_encode($arr);
      $this->save();
    }

    return $iCan;
  }

  public function addBayVideo($id)
  {
    if (strlen($this->pays_video) > 0) {
      $video = explode(',', $this->pays_video);
    } else {
      $video = array();
    }
    $video[] = $id;
    $this->pays_video = implode(',', $video);
    $this->save();
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
            'ratio' => 230 / 285,
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
        [['email', 'last_name', 'first_name', 'password_hash', 'favorites'], 'string', 'max' => 100],
        [['last_pays'], 'string'],
        [['tariff_unit'], 'string', 'max' => 400],
        [['username'], 'string', 'max' => 25],
        ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
        ['email', 'email'],
        ['password', 'string', 'min' => 6, 'max' => 61],
        [['sex', 'city', 'country', 'moderate', 'status', 'top', 'credits', 'tariff_end_date', 'tariff_id'], 'integer'],
        ['photo', 'file', 'extensions' => 'jpeg', 'on' => ['insert']],
        [['photo'], 'image',
            'minHeight' => 500,
            'maxSize' => 3 * 1024 * 1024,
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
    return (strlen($this->photo) > 5 ? $this->photo : '/img/not-ava.jpg');
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

  public function getTariff()
  {
    return $this->hasOne(TarificatorTable::className(), ['id' => 'tariff_id']);
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
  public function getChatInbox()
  {
    //$my_id=$this->id;
    //,'user_from'=>$my_id,'created_at>'.(time()-30*60*60*24)
    return $this->hasMany(Chat::className(), ['user_to' => 'id']);
  }

  /* Геттер для полного имени человека */
  public function getFullName()
  {
    return $this->last_name . ' ' . $this->first_name;
  }

  /* Геттер для полного ника человека */
  public function getFullNick()
  {
    $proile = $this->getProfile()->one();

    if ($proile && $proile->birthday != 0) {
      return $this->last_name . ' ' . date('Y', $proile->birthday);
    } else {
      return $this->last_name . ' ' . $this->first_name;
    }
  }

  public function getSexArray()
  {
    return array(0 => 'Men', 1 => 'Female');
  }

  public function isManager()
  {
    return ($this->getRoleOfUser($this->id, 'administrator') || $this->getRoleOfUser($this->id, 'moderator'));
  }

  public function getRoleOfUser($id, $roleName)
  {
    if (!isset($this->roles) || !is_array($this->roles)) {
      $roles = (new Query)
          ->select('item_name')
          ->from('auth_assignment')
          ->where(['user_id' => $id])
          ->all();
      $this->roles = array();
      if ($roles) {
        foreach ($roles as $role) {
          $this->roles[] = $role['item_name'];
        }
      }
    }
    return in_array($roleName, $this->roles);
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

  public static function findRandom($isOnline = false)
  {

    $where = array();
    if (Yii::$app->user->isGuest) {
      $where['user.sex'] = 1;
    } else {
      $where['user.sex'] = (1 - Yii::$app->user->identity->sex);
    }
    $where['moderate'] = 1;

    //ddd($where);
    $user = User::find()->where($where);
    if ($isOnline) {
      $user = $user->andFilterWhere(['and', ['>', 'last_online', time() - User::MAX_ONLINE_TIME]]);
      $cnt = $user->count();
      if ($cnt < 1) {
        $user = User::find()->where($where);
        $cnt = $user->count();
      }
    } else {
      $cnt = $user->count();
    }

    $cnt = rand(0, $cnt - 1);
    $user = $user->offset($cnt);
    $user = $user->limit(1);
    $user = $user->one();

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

  public static function getUserList($andWhere = false, $limit = 30)
  {
    $data = [];

    $url_param = [];
    $user = User::find()
        ->joinWith(['profile', 'city', 'role'])//добавляем вывод из связвнных таблиц
        ->where([
            'auth_assignment.user_id' => null, //убераем с выборки всех пользователей с ролями
        ])
        ->orderBy('top DESC');
    if ($andWhere) {
      $user = $user->andWhere($andWhere);
    }

    $data['list']=array(
        'lang'=>array(
            -1 => 'Any',
            0  => 'Unknown',
            1  => 'basic',
            2  => 'intermediate',
            3  => 'good',
            4  => 'excellent',
        ),
        'children'=>array(
            -1 => 'Any',
            0  => '0',
            1  => '1',
            2  => '2',
            3  => '3',
            4  => '4',
            5  => '5',
            6  => '6',
            7  => '7',
            8  => '8',
        ),

    );

    $get = Yii::$app->request->get();
    $page = isset($get['page']) ? (int)$get['page'] - 1 : 0;

    if (isset($get['id']) && $get['id']==(int)$get['id'] && $get['id']>0) {
      $g = array(
          'age-min' => 20,
          'age-max' => 80,
          'lang' =>-1,
          'children' =>-1,
          'id'=>(int)$get['id']
      );
      $user = $user
          ->andWhere(['=', 'user.id', $g['id']]);
    }else {
      if (isset($get['age-min']) && isset($get['age-max'])) {
        $g['age-min'] = (int)$get['age-min'];
        $g['age-max'] = (int)$get['age-max'];
        if ($g['age-min'] > $g['age-max']) {
          $c = $g['age-min'];
          $g['age-min'] = $g['age-max'];
          $g['age-max'] = $c;
        }

        if ($g['age-min'] < 18) $g['age-min'] = 18;
        if ($g['age-max'] < 18) $g['age-max'] = 18;
        if ($g['age-min'] > 80) $g['age-min'] = 80;
        if ($g['age-max'] > 80) $g['age-max'] = 80;

        $url_param = $g;
      } else {
        $g = array(
            'age-min' => 20,
            'age-max' => 80,
        );
      };

      if (
          isset($get['lang']) &&
          isset($data['list']['lang'][$get['lang']]) &&
          $get['lang'] > -1
      ) {
        $g['lang'] = (int)$get['lang'];
        $url_param['lang'] = $g['lang'];
        $user = $user
            ->andWhere(['>=', 'lang_proficiency', $g['lang']]);
      } else {
        $g['lang'] = -1;
      };

      if (
          isset($get['children']) &&
          isset($data['list']['children'][$get['children']]) &&
          $get['children'] > -1
      ) {
        $g['children'] = (int)$get['children'];
        $url_param['children'] = $g['children'];
        $user = $user
            ->andWhere(['=', 'children_count', $g['children']]);
      } else {
        $g['children'] = -1;
      };
    }
    $data['g'] = $g;

    $y = 60 * 60 * 24 * 356;
    $user = $user
        ->andWhere(['<', 'birthday', time() - $g['age-min'] * $y])
        ->andWhere(['>', 'birthday', time() - $g['age-max'] * $y]);


    if ($limit) {
      $count = $user->count();
      $max_page = ceil($count / $limit);
      if ($page < 0) $page = 0;
      if ($page >= $max_page) $page = $max_page - 1;
      $user = $user
          ->limit($limit)
          ->offset($limit * $page)
          ->all(); //выводим все что получилось
      $page++;
      $data['this_page'] = $page;
      $data['max_page'] = $max_page;
      $data['url_param'] = $url_param;
    } else {
      $user = $user
          ->all(); //выводим все что получилось
    }

    $data['b_url']=Yii::$app->request->getPathInfo();
    $data['user'] = $user;
    return $data;
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
    if ($oldValue && isset($oldValue['email']) && $oldValue['email'] != $this->email) {
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
        'last_online' => time(),
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

      if (exif_imagetype($photo->tempName) == 2) {
        $img = (new Image(imagecreatefromjpeg($photo->tempName)));
      } else {
        $img = (new Image($photo->tempName));
      }

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
  public function getUserPath($id)
  {
    $path = 'user_file/' . floor($id / 100) . '/' . ($id % 100) . '/';
    return $path;
  }

  public function rmdir($id)
  {
    //чистим папку файла
    $path = User::getUserPath($id);
    $files = glob($path . "*");
    foreach ($files as $file) {
      if (file_exists($file)) {
        if (is_file($file)) {
          unlink($file);
        } else {
          $files2 = glob($file . '/' . "*");
          foreach ($files2 as $file2) {
            if (file_exists($file2) && is_file($file2)) {
              unlink($file2);
            }
          }
          rmdir($file);
        }
      }
    }
    if (file_exists($path)) rmdir($path);
    return true;
  }

  public function videoIsOpen($id)
  {
    $video = explode(',', $this->pays_video);
    return in_array($id, $video);
  }

  public function getTariff_name()
  {
    return $this->tariff->name;
  }

  public function getTariffUnits()
  {
    $tariff = json_decode($this->tariff->includeData, true);
    if ($this->tariff->credits != 0) {
      $tariff['credits'] = $this->tariff->credits;
    }
    $useres_unit = json_decode($this->tariff_unit, true);

    $code = [];
    foreach ($tariff as $k => &$unit) {
      if ($unit) {
        $unit = [
            'start' => $unit,
            'users' => number_format($useres_unit[$k], $k == 'credits' ? 0 : 0, '.', ' ')
        ];
        $code[] = $k;
      } else {
        unset($tariff[$k]);
      }
    };

    $code = Tariff::find()
        ->where(['code' => $code])
        ->asArray()
        ->all();

    foreach ($code as &$unit) {
      $tariff[$unit['code']]['name'] = $unit['description'];
    };

    if (isset($tariff['credits'])) {
      $tariff['credits']['name'] = 'Credits';
    }
    return $tariff;
  }
}
