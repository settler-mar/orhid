<?php

namespace app\modules\mail\controllers;

use yii\web\Controller;
use app\components\timePassed;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use app\modules\mail\models\Mail;
use Yii;
use app\modules\fileupload\models\Fileupload;
use yii\db\Query;

/**
 * Default controller for the `mail` module
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
    public function actionIndex()
    {
      $my_id = Yii::$app->user->identity->id;

     $mails=Mail::find()
       ->select([
         'if(user_to='.$my_id.',user_from,user_to) as user_id',
         //'max(created_at) as created_at',
         'max(`id`) as msg_id',
         'sum(if(is_read=0 AND user_to='.$my_id.',1,0))as not_read', //Считаем сколько новых сообщений пльзователю
         'count(id)as cnt' //общее количество сообщений
       ])
       ->where(['or','user_from='.$my_id,'user_to='.$my_id])
       ->groupBy(array('user_from','user_to'));

      $mails = (new Query())
      ->from([
        'new_table' => $mails
        ])
      ->select([
        'user_id',
        'max(msg_id) as msg_id',
        'sum(not_read) as not_read',
        'sum(cnt) as cnt'
      ])
      ->groupBy(array('user_id'))
      ->orderBy(['not_read'=>SORT_DESC,'msg_id'=>SORT_DESC]);
      $mails=$mails->all();

      $users=[];
      $users_mails=[];
      foreach ($mails as $mail){
        $users[]=$mail['user_id'];
        $users_mails[]=$mail['msg_id'];
      }

      $users=User::find()->where(['id'=>$users])->all();
      $users_mails=Mail::find()->where(['id'=>$users_mails])->all();

      $user_=[];
      foreach ($users as $user){
        $user_[$user->id]=$user;
      }

      $users_mails_=[];
      foreach ($users_mails as $users_mail){
        $users_mails_[$users_mail->id]=$users_mail;
      }
      //d($mails);
      //d($user_);
      //ddd($users_mails_);

      return $this->render('index',[
        'mails_list'=>$mails,
        'users'=>$user_,
        'mails_detail'=>$users_mails_,
        'favorites'=>explode(',',Yii::$app->user->identity->favorites),
        'my_id'=>$my_id,
      ]);
    }

  public function actionMail($id)
  {
    $my_id = Yii::$app->user->identity->id;

    $where=[ 'auth_assignment.user_id'=>null];//убераем с выборки всех пользователей с ролями
    if($id>0){
      $where['user.id']= $id;
      $tpl='mail';
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


    if($id>0){
      $model = new Mail();
      //тут вписать правила отправки
      if ($model->load(Yii::$app->request->post())){
        $model->user_from=$my_id;
        $model->user_to=$id;
        $model->created_at=time();

        $model->message = strip_tags($model->message,'<img>,<p>,<strong>,<em>');
        $model->message = preg_replace("#[^а-яА-ЯA-Za-z0-9;:_.,?<>'\"/= -]+#u", '', $model->message);

        if($model->save()) {

        }
        return $this->redirect(['/mail/'.$id]);
      }

      Mail::updateAll(
        array('is_read'=>'1'),
        'user_to='.$my_id.' AND user_from='.$id);

      $mails = Mail::find()
        ->orWhere(['user_from'=>$id,'user_to'=>$my_id])
        ->orWhere(['user_from'=>$my_id,'user_to'=>$id])
        ->orderBy([
          'created_at' => SORT_DESC
        ])
        ->asArray()
        ->all();
    }else{
      $mails=NULL;
    }


    Yii::$app->view->registerJsFile('/js/redactor_plugin.js',  [
      'depends' => [\yii\imperavi\ImperaviRedactorAsset::className()]
    ]);
    Yii::$app->view->registerJsFile('/js/mail.js');

    return $this->render($tpl,[
      'user'=>$user,
      'm_id'=>$my_id,
      'mails'=>$mails,
      'model'=>new Mail(),
      'fileupload'=>new Fileupload(),
    ]);
  }
}
