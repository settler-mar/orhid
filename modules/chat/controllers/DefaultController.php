<?php

namespace app\modules\chat\controllers;

use yii\web\Controller;
use app\components\timePassed;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use Yii;
/**
 * Default controller for the `chat` module
 */
class DefaultController extends Controller
{
  function beforeAction($action) {
    $this->view->registerJsFile('/js/templates.js');
    return true;
  }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionChat($id)
    {
      if(Yii::$app->user->isGuest)
        throw new \yii\web\NotFoundHttpException('Page is available only to authorized users.');

      if(Yii::$app->user->identity->moderate!=1)
        throw new \yii\web\NotFoundHttpException('Page is available only after moderation profiles.');

      $my_id = Yii::$app->user->identity->id;

      $user=User::find()
        ->joinWith(['profile','role','city_','country_']) //добавляем вывод из связвнных таблиц
        ->where([
          'auth_assignment.user_id'=>null, //убераем с выборки всех пользователей с ролями
          'user.id' => $id
        ])
        //->asArray()
        ->one(); //выводим все что получилось
      if(!$user || ($user['moderate']!=1))
        throw new \yii\web\NotFoundHttpException('User not found or blocked');

      if($user['sex']==Yii::$app->user->identity->sex && !Yii::$app->user->identity->isManager())
        throw new \yii\web\NotFoundHttpException('No rights for access to chat with the user. Contact your administrator.');

      return $this->render('index',['user'=>$user,'my_id'=>$my_id]);
    }

    public function actionGet(){
      $out = array(
        'time' => time(),
        'users' => array()
      );

      /*$users = User::find()->joinWith(['profile','role'])->where([
        'auth_assignment.user_id'=>null, //убераем с выборки всех пользователей с ролями
        'sex'=>(1-Yii::$app->user->identity->sex),
        'moderate'=>1
      ])->all();*/
      $users = User::find()
        ->joinWith(['profile','role'])
        //->viaTable('{{%Chat}} b', ['b.user_to'=>'id'])
        //->select('user.*,profile.*,(SELECT count(*) FROM chat where')
        /*->select(['user.*', 'COUNT(book.id) AS booksCount'])
        ->groupBy(['user.id'])
        ->orderBy(['booksCount' => SORT_DESC])*/
        ->asArray()
        ->all();

      foreach($users as $user){
        $u=$user;
        /*$u=array(
          'id' => $user->id,
          'photo' => $user->getPhoto(),
          'username' => $user->username,
          'is_online' => ($out['time']-($user->last_online)<User::MAX_ONLINE_TIME),
          'last_online'=>TimePassed::widget(['timeStart'=>$user->last_online]),
          'msg_count' => $user->msgcnt,
          'msg_new' => $user->msgnew,
        );*/
        $out['users'][]=$u;
      };
      return json_encode($out);
    }
}
