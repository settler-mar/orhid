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
use app\modules\mail\models\Mail;
use app\modules\chat\models\Chat;

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
                'only' => ['registration', 'logout', 'confirm', 'reset', 'resetpassword', 'profile'],
                'rules' => [
                    [
                        'actions' => ['registration', 'confirm', 'reset', 'resetpassword'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
    /**
     * Деавторизация
     * @return \yii\web\Response
     */
    public function actionLogout(){
      $session = Yii::$app->session;
      $session->remove('admin_id');
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
    public function actionConfirm($token){
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //обработка поступивших данных
            Yii::$app
                ->getSession()
                ->setFlash(
                    'signup-success',
                    'Link to the registration confirmation sent to the Email.'
                );
            //return $this->redirect(['view', 'id' => 'user']);
        }


        //выводим стндартную форму
        return $this->render('registration.jade', [
            'model' => $model,
        ]);
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
            return $this->goHome();
        }

        return $this->render('resetpaessword', [
            'forget' => $forget
        ]);

    }

    //добавление пользователя в избранне
    public function actionFav(){

      $out=[
        'message'=>"Error to edit favorites.",
        'type'=>'err',
        'title'=>"ERROR"
      ];

      $this_user=User::findOne(Yii::$app->user->identity->id);

      $request = Yii::$app->request;
      $user=(int)$request->post('user');
      $status=(int)$request->post('status');

      $favorites=Yii::$app->user->identity->favorites;
      if(strlen($favorites)>0){
        $favorites=explode(',',$favorites);
        $i=array_search($user,$favorites);
      }else{
        $favorites=[];
        $i=false;
      }

      if($status==0){
        if($i===false){
          $out['message']='The man was not found in the favorites.';
        }else {
          unset ($favorites[$i]);
          $this_user->favorites = implode(',', $favorites);
          $this_user->save();
          $out = [
            'message' => "Man removed from favorites.",
            'type' => 'info'
          ];
        }
      }

      if($status==1){
        if($i!==false){
          $out['message']='Man already in favorites.';
        }else {
          $favorites[]=$user;
          $this_user->favorites = implode(',', $favorites);
          $this_user->save();
          $out = [
            'message' => "Man is added to the favorites.",
            'type' => 'info'
          ];
        }

      }
      return json_encode($out);
    }
    /**
     * Сброс пароля через электронную почту
     * @param $token - токен сброса пароля, высылаемый почтой
     * @param $password - новый пароль
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionReset($token, $password){
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

    public function actionOnline(){
      if(Yii::$app->user->isGuest)return;

      User::getDb()->createCommand()->update(User::tableName(), [
            'last_online'=> time(),
        ], ['id' => Yii::$app->user->id])->execute();

      $mails=Mail::find()
        ->where(['is_read'=>0,'user_to'=>Yii::$app->user->identity->id])->asArray()->all();

      $chat=Chat::find()
        ->where(['is_read'=>0,'user_to'=>Yii::$app->user->identity->id])->asArray()->all();

      return json_encode([
        'mails'=>count($mails),
        'chat'=>count($chat),
      ]);
    }

  public function actionReturnToAdmin(){
    $session = Yii::$app->session;
    $last_admin_id=$session->get('admin_id');
    if(!$last_admin_id) {
      throw new BadRequestHttpException("Access is denied");
    }

    $identity = User::findIdentity($last_admin_id);
    Yii::$app->user->login($identity);

    return $this->redirect(['/']);
  }
    /**
     * Профиль пользователя (личный кабинет)
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionProfile()
    {

        $model = ProfileForm::findOne(Yii::$app->user->id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page could not be found.');
        }

        if($model->sex==0){
             $profile=ProfileMale::findIdentity(Yii::$app->user->id);
        }else{
            $profile=ProfileFemale::findIdentity(Yii::$app->user->id);
        }
        if (!$profile) {
            // INSERT
            Yii::$app->db->createCommand()->insert('profile', [
                'user_id' => Yii::$app->user->id
            ])->execute();
            if($model->sex==0){
                $profile=ProfileMale::findIdentity(Yii::$app->user->id);
            }else{
                $profile=ProfileFemale::findIdentity(Yii::$app->user->id);
            }
        }

        //return ;

        $post=Yii::$app->request->post();
        if(isset($post['ProfileForm'])){
            //удаляем картинку до сохранения
            $post['ProfileForm']['photo']=$model->photo;
            //добавляем метку обновления
            $post['ProfileForm']['updated_at'] = date("Y-m-d H:i:s");;
        }

        $request = Yii::$app->request;
        if($request->isPost) {
            $to_save = false;

            $post['ProfileForm']['moderate']=$model->moderate;
            if(isset($post['moderate-button'])){
                $post['ProfileForm']['moderate']=1;
            }

            //Готовим профиль к сохранению
            if ($profile->load($post) && $profile->validate()) {
                $to_save = true;
            }

            //Готовим пользователя к сохранению
            if ($model->load($post) && $model->validate()) {
                $to_save = true && $to_save;
            } else {
                $to_save = false;
            }

            //Если номально отвалидировало отправляем на сохранение
            if($to_save && $profile->save() && $model->save()){
                //При успешнос мохранении обновляем страницу и выводим сообщение
                Yii::$app->getSession()->setFlash('success', 'The profile updated.');
                return $this->redirect(['profile']);
            }
        }

        // Преобразуем дату в понятный формат
        if ($profile->birthday) {
            $profile->birthday = Date('M  j,Y',$profile->birthday);
        }

        return $this->render('profile', [
            'model' => $model,
            'profile' =>$profile
        ]);

    }


}