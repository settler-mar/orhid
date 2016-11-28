<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use app\modules\user\models\EmailConfirm;
use app\modules\user\models\forms\RegistrationForm;
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
                'only' => ['login', 'signup', 'logout', 'confirm', 'reset', 'profile', 'remove', 'online', 'show',
                    'index', 'view', 'update', 'delete', 'rmv', 'multiactive', 'multiblock', 'multidelete'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'confirm', 'reset', 'show'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login', 'signup', 'show', 'logout', 'profile', 'remove', 'online'],
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
            echo 'ok-';
            // Авторизируемся при успешном подтверждении
            echo $user_id;
            $identity =User::findIdentity($user_id);
            Yii::$app->user->login($identity);
        }
        return $this->redirect(['/']);
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
}