<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\modules\user\models\Profile;
use app\modules\user\models\User;

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
         return $this->render('index.jade');
    }

    public function actionLegends()
    {
         return $this->render('legends.jade');
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
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
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
                //,'moderate'=>1, //только прошедшие модерацию
            ])

            //->asArray()
            ->all(); //выводим все что получилось

        return $this->render('top',['user'=>$user]);
    }


     /**
     * @return user page
     */
    public function actionUser($id)
    {
        //throw new \yii\web\NotFoundHttpException('Page');
        $user=User::find()
            ->joinWith(['profile','city','country','role']) //добавляем вывод из связвнных таблиц
            ->where([
                'auth_assignment.user_id'=>null, //убераем с выборки всех пользователей с ролями
                'user.id' => $id
            ])
            //->asArray()
            ->one(); //выводим все что получилось
        if(!$user || ($user['sex']==0 && $user['moderate']!=1))
            throw new \yii\web\NotFoundHttpException('User not found or blocked');

        return $this->render('user',['model'=>$user]);
    }
}
