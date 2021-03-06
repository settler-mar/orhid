<?php

namespace app\controllers;

use Yii;
use app\modules\staticPages\models\StaticPages;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\modules\user\models\Profile;
use app\modules\user\models\User;
use app\modules\tarificator\models\TarificatorTable;
use app\modules\tariff\models\Tariff;

class SiteController extends Controller
{
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'only' => ['logout'],
            'rules' => [
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function actions()
  {
    return [
        'error' => [
            'class' => 'yii\web\ErrorAction',
        ],
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
        ],
    ];
  }

  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex()
  {
    $page = StaticPages::find()->where(['id' => 1])->asArray()->one();
    $user = User::find()
        ->joinWith(['profile', 'city', 'role'])//добавляем вывод из связвнных таблиц
        ->where([
            'auth_assignment.user_id' => null, //убераем с выборки всех пользователей с ролями
            'user.sex' => 1, //Только женщины
            'moderate' => 1, //только прошедшие модерацию
          // 'user.id'=>[5, 6, 7, 8, 21, 23],
        ])
        ->andWhere(['>', 'last_online', time() - User::MAX_ONLINE_TIME])
        /*$cnt=$user->count();
        if($cnt>6){
          $offset=random_int(0,$cnt-6);
        }else{
          $offset=0;
        };
        $user=$user*/
        //  ->offset($offset)
        //->orderBy(['top' => SORT_DESC])
        ->orderBy(['last_online' => SORT_DESC])
        ->limit(6)
        ->all();
    //->all(); //выводим все что получилось

    return $this->render('index.jade', ['page' => $page, 'user' => $user]);
  }

  public function actionServices()
  {
    $tarificatorTariffs = TarificatorTable::find()->where('credits = :credits', [':credits' => 0])->all();
    $tarificatorCredits = TarificatorTable::find()->where(['not in', 'credits', [0]])->all();
    $tariffElements_tmp = Tariff::find()->asArray()->all();
    $tariffElements = array();
    foreach ($tariffElements_tmp as $tariffElement) {
      $tariffElements[$tariffElement['code']] = $tariffElement;
    }

    $guest = Yii::$app->user->isGuest;
    $page = array(
        'title' => 'Services',
        'meta_title' => 'Services',
        'index' => 1,
    );
    //return redirect
    return $this->render('services.jade', [
        'page' => $page,
        'guest' => $guest,
        'tarificatorTariffs' => $tarificatorTariffs,
        'tarificatorCredits' => $tarificatorCredits,
        'tariffElements' => $tariffElements,
    ]);
  }

  public function actionStories()
  {
    $page = StaticPages::find()->where(['id' => 9])->asArray()->one();
    return $this->render('stories.jade', ['page' => $page]);
  }


  /**
   * Login action.
   *
   * @return string
   */
  public function actionLogin()
  {
    if (!Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $model = new LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
      return $this->goBack();
    }
    return $this->render('login', [
        'model' => $model,
    ]);
  }

  /**
   * Logout action.
   *
   * @return string
   */
  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }

  /**
   * Displays contact page.
   *
   * @return string
   */
  public function actionOnlinehelp()
  {
    $model = new ContactForm();
    if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
      Yii::$app->session->setFlash('contactFormSubmitted');

      return $this->refresh();
    }

    $page = StaticPages::find()->where(['id' => 15])->asArray()->one();
    return $this->render('contact', [
        'model' => $model,
        'page' => $page
    ]);
  }

  /**
   * Displays about page.
   *
   * @return string
   */
  public function actionAbout()
  {
    $page = StaticPages::find()->where(['id' => 7])->asArray()->one();
    return $this->render('about', ['page' => $page]);
  }

  /**
   * Displays about page.
   *
   * @return string
   */
  public function actionTop()
  {
    $data = User::getUserList([
        'user.sex' => 1, //Только женщины
        'moderate' => 1, //только прошедшие модерацию
        'top' => 1
    ],false);

    //  foreach ($user as $qqq){
    //  ddd($qqq->relatedRecords['city']['city']);}

    $data['page'] = StaticPages::find()->where(['url' => 'top'])->asArray()->one();
    return $this->render('top', $data);
  }

  public function actionLadies()
  {
    $data = User::getUserList([
      'user.sex' => 1, //Только определенного женщины
      'moderate' => 1, //только прошедшие модерацию
    ]);

    $data['page'] = StaticPages::find()->where(['url' => 'ladies'])->asArray()->one();
    return $this->render('mans', $data);
  }

  public function actionMen()
  {
    $data = User::getUserList([
        'user.sex' => 0, //Только определенного мужчины
        //'moderate' => 1, //только прошедшие модерацию
    ]);

    $data['page'] = StaticPages::find()->where(['url' => 'men'])->asArray()->one();
    return $this->render('mans', $data);
  }

  /**
   * @return user page
   */
  public function actionUser($id)
  {
    if (isset($_GET['open_video'])) {
      if (Yii::$app->user->identity->canIdo("video_intro")) {
        Yii::$app->user->identity->addBayVideo($id);
        Yii::$app->session->setFlash('success', 'Successful payment. Enjoy watching.');
      } else {
        Yii::$app->session->setFlash('error', 'There is not enough money on the account. Refill the balance.');
      }
      return $this->redirect('/user/' . $id);
    }

    $user = User::find()
        ->joinWith(['profile', 'city_', 'country_', 'role'])//добавляем вывод из связвнных таблиц
        ->where([
            'auth_assignment.user_id' => null, //убераем с выборки всех пользователей с ролями
            'user.id' => $id
        ])
        //->asArray()
        ->one(); //выводим все что получилось

    if (!$user || ($user['moderate']==1 && $user['moderate'] != 1))
      throw new \yii\web\NotFoundHttpException('User not found or blocked');


    if (Yii::$app->user->isGuest) {
      $v = 0;
      $adm = 0;
    } else {
      $u = User::findOne(Yii::$app->user->id);
      $v = $u->sex + 1;
      $adm = count($u->role);
    }

    return $this->render('user', ['model' => $user, "v" => $v, 'is_admin' => $adm]);
  }
}
