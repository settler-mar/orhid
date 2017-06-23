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
      $page=StaticPages::find()->where(['id' => 1])->asArray()->one();
      return $this->render('index.jade',['page'=>$page]);
    }

    public function actionServices()
    {
      $tarificatorTariffs = TarificatorTable::find()->where('credits = :credits', [':credits' => 0])->all();
      $tarificatorCredits = TarificatorTable::find()->where(['not in', 'credits', [0]])->all();
      $tariffElements_tmp = Tariff::find()->asArray()->all();
      $tariffElements=array();
      foreach ($tariffElements_tmp as $tariffElement){
        $tariffElements[$tariffElement['code']
        ]=$tariffElement;
      }

      $guest = Yii::$app->user->isGuest;
      $page=array(
        'title'=>'Services',
        'meta_title'=>'Services',
        'index'=>1,
      );
        //return redirect
      return $this->render('services.jade',[
          'page'=>$page,
          'guest'=> $guest,
          'tarificatorTariffs' =>$tarificatorTariffs,
          'tarificatorCredits' => $tarificatorCredits,
          'tariffElements' => $tariffElements,
      ]);
    }

    public function actionLegends()
    {
      $page=StaticPages::find()->where(['id' => 9])->asArray()->one();
      return $this->render('legends.jade',['page'=>$page]);
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

        $page=StaticPages::find()->where(['id' => 15])->asArray()->one();
        return $this->render('contact', [
            'model' => $model,
            'page'=>$page
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
      $page=StaticPages::find()->where(['id' => 7])->asArray()->one();
      return $this->render('about',['page'=>$page]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionTop()
    {
        $user=User::find()
            ->joinWith(['profile','city','role']) //добавляем вывод из связвнных таблиц
            ->where([
                'auth_assignment.user_id'=>null, //убераем с выборки всех пользователей с ролями
                'user.sex' => 1, //Только женщины
                'moderate'=>1, //только прошедшие модерацию
            ])

            //->asArray()
            ->all(); //выводим все что получилось
    //  foreach ($user as $qqq){
      //  ddd($qqq->relatedRecords['city']['city']);}

        $page=StaticPages::find()->where(['id' => 3])->asArray()->one();
        return $this->render('top',['user'=>$user,'page'=>$page]);
    }

    public function actionMans()
    {
      $user=User::find()
        ->joinWith(['profile','city','role']) //добавляем вывод из связвнных таблиц
        ->where([
          'auth_assignment.user_id'=>null, //убераем с выборки всех пользователей с ролями
          'user.sex' => 0, //Только мужчины
          'moderate'=>1, //только прошедшие модерацию
        ])

        //->asArray()
        ->all(); //выводим все что получилось
      $page=StaticPages::find()->where(['id' => 11])->asArray()->one();
      return $this->render('mans',['user'=>$user,'page'=>$page]);
    }

     /**
     * @return user page
     */
    public function actionUser($id)
    {
        $user=User::find()
            ->joinWith(['profile','city_','country_','role']) //добавляем вывод из связвнных таблиц
            ->where([
                'auth_assignment.user_id'=>null, //убераем с выборки всех пользователей с ролями
                'user.id' => $id
            ])
            //->asArray()
            ->one(); //выводим все что получилось
        if(!$user || $user['moderate']!=1)
            throw new \yii\web\NotFoundHttpException('User not found or blocked');



        if(Yii::$app->user->isGuest){
          $v=0;
          $adm=0;
        }else{
          $u=User::findOne(Yii::$app->user->id);
          $v=$u->sex+1;
          $adm=count($u->role);
        }

        return $this->render('user',['model'=>$user,"v"=>$v,'is_admin'=>$adm]);
    }
}
