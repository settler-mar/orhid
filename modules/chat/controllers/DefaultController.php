<?php

namespace app\modules\chat\controllers;

use yii\web\Controller;
use app\components\timePassed;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use app\modules\chat\models\Chat;
use Yii;
/**
 * Default controller for the `chat` module
 */
class DefaultController extends Controller
{
  function beforeAction($action) {
    $this->view->registerJsFile('/js/templates.js');

    if(Yii::$app->user->isGuest)
      throw new \yii\web\NotFoundHttpException('Page is available only to authorized users.');

    if(Yii::$app->user->identity->moderate!=1)
      throw new \yii\web\NotFoundHttpException('Page is available only after moderation profiles.');

    if(count(Yii::$app->user->identity->role)>0){
      throw new \yii\web\NotFoundHttpException('Page is available only to non-administrators users.');
    };
    /*$u=User::findOne(Yii::$app->user->id);
    $v=$u->sex+1;
    $adm=count($u->role);*/

    return true;
  }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionChat($id)
    {
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
      if (!Yii::$app->request->isAjax) {
        return 'Only on Ajax';
      }
      $out = array(
        'time' => time(),
        'users' => array()
      );

      $my_id=Yii::$app->user->identity->id;
      $request = Yii::$app->request;


      $users_arr=array($request->post('user'));
      $users_data=array();
      $users = Chat::find()
        ->select([
            'user_to','user_from',
            'count(id) as cnt',
            'sum(if(is_read=0,1,0)) as new',
            'MAX(created_at) as created_at'
        ])
        ->andWhere(['user_to'=>$my_id])
        ->orWhere(['user_from'=>$my_id])
        ->andWhere(['>','created_at',time()-30*24*60*60])
        ->groupBy(['user_to','user_from'])
        ->asArray()
        ->all();

      foreach($users as $user){
        if($user['user_from']==$my_id){
          $u_id=$user['user_to'];
          $u=array(
            'out'=>(int)$user['cnt'],
            'out_new'=>(int)$user['new'],
            'out_time'=>(int)$user['created_at'],
          );
        }else{
          $u_id=$user['user_from'];
          $u=array(
            'in'=>(int)$user['cnt'],
            'in_new'=>(int)$user['new'],
            'in_time'=>(int)$user['created_at'],
          );
        }
        if(!isset($users_data[$u_id]))$users_data[$u_id]=array();
        $users_data[$u_id]=$users_data[$u_id]+$u;
        $users_arr[]=$u_id;
      };

      $users = User::find()
        ->joinWith(['profile','role'])
        ->where(['id' => $users_arr,'moderate'=>1])
        ->all();

      foreach($users as $user) {
        $u=array(
          'id' => $user->id,
          'photo' => $user->getPhoto(),
          'username' => $user->username,
          'is_online' => ($out['time']-($user->last_online)<User::MAX_ONLINE_TIME),
          'last_online'=>TimePassed::widget(['timeStart'=>$user->last_online]),
        );
        if($request->post('last_msg')==$user->id){
          $out['user']=$u;
        }
        if(isset($users_data[$user->id])) {
          $out['users'][] = $u + $users_data[$user->id];
        }
      }

      $time=($request->post('last_msg',false)?$request->post('last_msg'):0);
      $chat = Chat::find()
        ->andWhere(['user_to'=>$my_id])
        ->orWhere(['user_from'=>$my_id])
        ->andWhere(['>', 'created_at', $time])
        ->andWhere(['>', 'created_at', time() - 30 * 24 * 60 * 60])
        ->asArray()
        ->all();

      $out['chat']=array();
      $clear_new=false;
      foreach($chat as $message) {
        $message['created_at_str']=date('H:i M j',$message['created_at']);
        $message['dop_class']='admin_'.($message['user_to']==$my_id?1:0);
        if($message['user_to']==$my_id)$clear_new=true;
        $out['chat'][] = $message;
      }

      if($clear_new){
        Chat::updateAll(array('is_read'=>'1'),'user_to='.$my_id.' AND user_from='.$request->post('user'));
      }
      return json_encode($out);
    }

  public function actionSend(){
    if (!Yii::$app->request->isAjax) {
      return 'Only on Ajax';
    }
    $out = array(
      'time' => time(),
      'status'=>0 //0 это все норм.
    );

    $my_id=Yii::$app->user->identity->id;
    $request = Yii::$app->request;


    //тут должна быть проверка на возможность отправки сообщения и выдаа сообщений об ошибке

    // вставить новую строку данных
    $message = new Chat();
    $message->user_from = $my_id;
    $message->user_to = $request->post('to');
    $message->is_read = 0;
    $message->message = strip_tags($request->post('message'),'<img>');
    $message->created_at = time();
    $message->save();



    return json_encode($out);
  }
}
