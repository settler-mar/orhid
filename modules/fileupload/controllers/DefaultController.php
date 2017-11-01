<?php

namespace app\modules\fileupload\controllers;

use yii\web\Controller;
use app\components\timePassed;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use app\modules\chat\models\Chat;
use Yii;
use app\modules\fileupload\models\Fileupload;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Default controller for the `chat` module
 */
class DefaultController extends Controller
{
  public function actions()
  {
    return [
    ];
  }

  public function actionUpload($id=null){
    if (!Yii::$app->request->isAjax) {
      return 'Only on Ajax';
    }
    $request = Yii::$app->request;
    $model = new Fileupload();
    $response = [];
    if ($request->isPost) {
      Yii::$app->response->getHeaders()->set('Vary', 'Accept');
      Yii::$app->response->format = Response::FORMAT_JSON;
      $model->image = UploadedFile::getInstance($model, 'image');
      if ($model->upload($id)) {
        $response['files'][] =
          [
            'name' => $model->image->name,
            'type' => $model->image->type,
            'size' => $model->image->size,
            'url' => $model->getImageUrl(),
            'thumbnailUrl' => $model->getImageUrl(),
            'deleteUrl' => Url::to([
              'delete', 'id' => 1
            ]),
            'deleteType' => 'POST'
          ];
      }else{
        $response[] = ['error' => 'Unable to save picture'];
      }
      @unlink($model->image->tempName);
    }
    return $response;
  }

  public function actionGet(){
    if (isset($_POST['legend'])){
      $path = 'legends_files/'.$_POST['legend'].'/';
    }else{
      $path=\Yii::$app->user->identity->userDir.'upload/';
    }
    //$path=Yii::$app->basePath.$path;

    if (!file_exists($path)) {
      mkdir($path, 0777, true);   // Создаем директорию при отсутствии
    };

    $file_list=[];
    if ($handle = opendir($path)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          $file_list[]= str_replace("//",'/','/'.$path.$entry);
        }
      }
      closedir($handle);
    }

    Yii::$app->response->getHeaders()->set('Vary', 'Accept');
    Yii::$app->response->format = Response::FORMAT_JSON;

    return $file_list;
  }
}