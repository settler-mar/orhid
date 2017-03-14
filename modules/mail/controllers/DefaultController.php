<?php

namespace app\modules\mail\controllers;

use yii\web\Controller;
use app\components\timePassed;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use app\modules\mail\models\Mail;
use Yii;
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
        return $this->render('index');
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

        $model->message = strip_tags($model->message,'<img>');
        $model->message = preg_replace("#[^а-яА-ЯA-Za-z0-9;:_.,?<>'\"/= -]+#u", '', $model->message);

        if($model->save()) {

        }
      }

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


    return $this->render($tpl,[
      'user'=>$user,
      'mails'=>$mails,
      'model'=>new Mail()
    ]);
  }
}
