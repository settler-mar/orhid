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
      /*'upload' => [
        'class' => 'troy\ImageUpload\UploadAction',
        'successCallback' => [$this, 'successCallback'],
        'beforeStoreCallback' => [$this,'beforeStoreCallback']
      ],*/
    ];
  }

  public function actionUpload(){
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
      if ($model->upload()) {
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

  public function successCallback($store,$file){
  }
  public function beforeStoreCallback($file){
  }
}