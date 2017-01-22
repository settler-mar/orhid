<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;


class ServiceController extends Controller
{
  public function behaviors()
  {
    return [

    ];
  }

  function beforeAction($action) {

    if (Yii::$app->user->isGuest || !Yii::$app->user->can('serviceCommands')) {
      throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
      return false;
    }

    $this->view->registerJsFile('/js/bootstrap.min.js');
    $this->view->registerJsFile('/js/admin.js');
    $this->view->registerCssFile('/css/bootstrap.min.css');
    $this->view->registerCssFile('/css/admin.css',['depends'=>['app\assets\AppAsset']]);
    return true;
  }

  public function actionIndex(){
    return time();
  }

  public function actionAuth_cash_clean()
  {

    $auth = Yii::$app->authManager;
    $auth->invalidateCache();

    return 'Clean admin rulls';
  }


}
