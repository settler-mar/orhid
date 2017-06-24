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

  public function actionIndex()
  {
    return $this->actionChat(0);
  }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionChat($id)
    {
      $my_id = Yii::$app->user->identity->id;

      $where=[ 'auth_assignment.user_id'=>null];//убераем с выборки всех пользователей с ролями
      if($id>0){
        $where['user.id']= $id;
        $tpl='chat';
        $user=User::find()
          ->joinWith(['profile','role','city_','country_']) //добавляем вывод из связвнных таблиц
          ->where($where);
        $user=$user->one();
      }else{
        $tpl='index';
        $user=User::findRandom();
      }

      if(!$user || ($user->moderate!=1))
        throw new \yii\web\NotFoundHttpException('User not found or blocked');

      //d($user);
      //ddd(Yii::$app->user->identity);
      //if((($user['sex']==1)&&($user->canIdo('chatUnit')!=1)) && (!Yii::$app->user->identity->isManager()))

      if($user->sex==Yii::$app->user->identity->sex || (Yii::$app->user->identity->isManager()))
        throw new \yii\web\NotFoundHttpException('No rights for access to chat with the user. Contact your administrator.');

      return $this->render($tpl,['user'=>$user,'my_id'=>$my_id,'user_id'=>$id]);
    }

    public function actionGet(){
      if (!Yii::$app->request->isAjax) {
        return 'Only on Ajax';
      }

      $request = Yii::$app->request;
      $my_id=Yii::$app->user->identity->id;
      $user_from=$request->post('user');

      $out = array(
        'time' => (int)$request->post('last_msg'),
        'users' => array()
      );

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
          'is_online' => (time()-($user->last_online)<User::MAX_ONLINE_TIME),
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
        ->andWhere(['>', 'id', $time])
        //->andWhere(['>', 'created_at', $time])
        //->andWhere(['>', 'created_at', time() - 30 * 24 * 60 * 60])
        ->andWhere(['>', 'created_at', time() - 15 * 60])
        ->orWhere(['is_read'=>0,'user_to'=>$my_id,'user_from'=>$user_from])
        ->orderBy([
          'created_at' => SORT_ASC
        ])
        ->asArray()
        ->all();

      $out['chat']=array();
      $clear_new=false;
      //ddd($chat);
      foreach($chat as $message) {
        $message['created_at_str']=date('H:i M j',$message['created_at']);
        $message['dop_class']='admin_'.($message['user_to']==$my_id?1:0);
        if($message['user_to']==$my_id)$clear_new=true;
        if($message['id']>$out['time'])$out['time']=$message['id'];
        $out['chat'][] = $message;
      }

      //ddd($clear_new);
      if($clear_new){
        Chat::updateAll(array('is_read'=>'1'),'user_to='.$my_id.' AND user_from='.$user_from);
      }

      if(strlen(Yii::$app->user->identity->favorites)>0){
        $out['favorites']=explode(',',Yii::$app->user->identity->favorites);
      }else{
        $out['favorites']=[];
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

    $user = User::find()->where(['id'=>Yii::$app->user->id])->one();

     /* if ((Yii::$app->user->identity->sex==1)&&($user->canIdo('chatUnit')!=1)) {
          $out = array(
              'time' => time(),
              'status'=>1, //0 это все норм.
              'msg' => 'Not enough chatUnit in your tariff for Messenger'
          );
          return json_encode($out);
      }*/

    // вставить новую строку данных
    $message = new Chat();
    $message->user_from = $my_id;
    $message->user_to = $request->post('to');
    $message->is_read = 0;
    $message->message = strip_tags($request->post('message'),'<img><div>');
    $message->message = preg_replace("#[^0-9а-яА-ЯA-Za-z;:_.,?<>'\"/= -]+#u", '', $message->message);
    $message->created_at = time();
    $message->save();

    return json_encode($out);
  }
}
