<?php

namespace app\modules\shop\controllers;

use Yii;
use app\modules\shop\models\ShopStore;
use app\modules\shop\models\ShopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\user\models\User;

/**
 * Default controller for the `shop` module
 */
class DefaultController extends Controller
{
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
      $user=User::findOne($id);

      return $this->render('UserGift',[
        "shop"=>$shop,
        "user_id"=>$id,
        "user"=>$user
      ]);
    }

    public function actionUserGift2($id,$code)
    {
      $shop=ShopStore::find()->where(['active'=>1])->asArray()->one();
      $user=User::findOne($id);

      return $this->render('UserGift2',[
        "shop"=>$shop,
        "user_id"=>$id,
        "user"=>$user
      ]);
    }
}
