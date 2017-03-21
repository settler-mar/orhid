<?php

namespace app\modules\shop\controllers;

use Yii;
use app\modules\shop\models\ShopStore;
use app\modules\shop\models\ShopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\user\models\User;
use app\modules\shop\models\ShopOrder;
/**
 * Default controller for the `shop` module
 */
class DefaultController extends Controller
{
  public function beforeAction($action)
  {
    // ...set `$this->enableCsrfValidation` here based on some conditions...
    // call parent method that will check CSRF if such property is true.
    if ($action->id === 'user-gift2') {
      # code...
      $this->enableCsrfValidation = false;
    }
    return parent::beforeAction($action);
  }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUserGift($id)
    {
      $shop=ShopStore::find()->where(['active'=>1])->asArray()->all();
      $user=User::find()->where(['id'=>$id,'sex'=>1])->one();

      return $this->render('UserGift',[
        "shop"=>$shop,
        "user_id"=>$id,
        "user"=>$user
      ]);
    }

    public function actionUserGift2($id,$code)
    {
      $shop=ShopStore::find()->where(['active'=>1,'id'=>$code])->asArray()->one();

      if(!$shop){
        return $this->redirect(['/user-gift/'.(int)$id]);
      };
      $user=User::find()->where(['id'=>$id,'sex'=>1])->one();

      $request=Yii::$app->request;

      if($request->isPost && $user){
        $present = new ShopOrder();
        $present->user_from=Yii::$app->user->getId();;
        $present->user_to=$id;
        $present->item_id=$code;
        $present->price=$shop['price'];
        $present->user_comment=$request->post('comments');
        $present->created_at=time();
        $present->save();
        return $this->redirect(['/payment/shop/'.$present->id]);
      }

      return $this->render('UserGift2',[
        "shop"=>$shop,
        "user_id"=>$id,
        "user"=>$user,
        "request"=>$request->post()
      ]);
    }
}
