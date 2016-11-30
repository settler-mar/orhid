<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\Profile;
use app\modules\user\models\profile\ProfileMale;
use app\modules\user\models\profile\ProfileFemale;
use app\modules\user\models\UserSearch;
use app\modules\user\models\EmailConfirm;
use app\modules\user\models\forms\RegistrationForm;
use app\modules\user\models\forms\PasswordResetForm;
use app\modules\user\models\forms\ProfileForm;
use app\modules\user\models\ResetPassword;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class UserController extends Controller
{
    /**
     * Разделение ролей
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'registration', 'logout', 'confirm', 'reset', 'resetpassword', 'profile', 'remove', 'online', 'show',
                    'index', 'view', 'update', 'delete', 'rmv', 'multiactive', 'multiblock', 'multidelete'],
                'rules' => [
                    [
                        'actions' => ['login', 'registration', 'confirm', 'reset', 'resetpassword', 'show'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login', 'registration', 'show', 'logout', 'profile', 'remove', 'online'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['userManager'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['userView'],
                    ],
                    [
                        'actions' => ['update', 'rmv', 'multiactive', 'multiblock'],
                        'allow' => true,
                        'roles' => ['userUpdate'],
                    ],
                    [
                        'actions' => ['delete', 'multidelete'],
                        'allow' => true,
                        'roles' => ['userDelete'],
                    ],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'tryrty4553454' : null,
            ],
        ];
    }

    /**
     * Деавторизация
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Подтверждение аккаунта с помощью
     * электронной почты
     * @param $token - токен подтверждения, высылаемый почтой
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionConfirm($token)
    {
        try {
            $model = new EmailConfirm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user_id = $model->confirmEmail()) {
            //echo 'ok-';
            // Авторизируемся при успешном подтверждении
            //echo $user_id;
            $identity = User::findIdentity($user_id);
            Yii::$app->user->login($identity);
        }
        return $this->redirect(['/']);
    }

    /**
     * Сброс пароля
     * @return string|\yii\web\Response
     */
    public function actionResetpassword()
    {
        // Уже авторизированных отправляем на домашнюю страницу
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        //Восстановление пароля
        $forget = new PasswordResetForm();
        if ($forget->load(Yii::$app->request->post()) && $forget->validate()) {
            if ($forget->sendEmail()) { // Отправлено подтверждение по Email
                Yii::$app->getSession()->setFlash('reset-success', 'Link to the activation of a new password sent to the Email.');
            }
            //return $this->goHome();
        }

        return $this->render('resetpaessword', [
            'forget' => $forget
        ]);

    }

    /**
     * Сброс пароля через электронную почту
     * @param $token - токен сброса пароля, высылаемый почтой
     * @param $password - новый пароль
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionReset($token, $password)
    {
        try {
            $model = new ResetPassword($token, $password);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user_id = $model->resetPassword()) {
            // Авторизируемся при успешном сбросе пароля
            Yii::$app->user->login(User::findIdentity($user_id));
        }
        return $this->redirect(['/']);
    }

    /**
     * Профиль пользователя (личный кабинет)
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionProfile()
    {
        /** @var \lowbase\user\models\forms\ProfileForm $model */
        $model = ProfileForm::findOne(Yii::$app->user->id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page could not be found.');
        }

        if($model->sex==0){
                        $profile=ProfileMale::findOne(Yii::$app->user->id);
        }else{
            $profile=ProfileFemale::findOne(Yii::$app->user->id);
        }
        if ($profile === null) {
            // INSERT
            Yii::$app->db->createCommand()->insert('profile', [
                'user_id' => Yii::$app->user->id
            ])->execute();
            if($model->sex==0){
                $profile=ProfileMale::findOne(Yii::$app->user->id);
            }else{
                $profile=ProfileFemale::findOne(Yii::$app->user->id);
            }
            //throw new NotFoundHttpException(Yii::t('user', 'The requested page could not be found.'));
        }

        /*// Преобразуем дату в понятный формат
        if ($model->birthday) {
            $date = new \DateTime($model->birthday);
            $model->birthday = $date->format('d.m.Y');
        }*/

        $post=Yii::$app->request->post();
        if(isset($post['ProfileForm'])){
            //удаляем картинку до сохранения
            $post['ProfileForm']['photo']=$model->photo;
            //добавляем метку обновления
            $post['ProfileForm']['updated_at'] = time();
        }

        if ($model->load($post)) {
            // Получаем изображение, если оно есть
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'The profile updated.');
                return $this->redirect(['profile']);
            }
        }

        //$profile->intro_age='';
        return $this->render('profile', [
            'model' => $model,
            'profile' =>$profile
        ]);

    }
}