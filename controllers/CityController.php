<?php

namespace app\controllers;

use app\models\LbCity;
use yii;

class CityController extends \yii\web\Controller
{
    public function actionGet($id = null)
    {
        $cache=Yii::$app->cache;
        $cache_key='countrys_city_'.$id;
        $citys=$cache->get($cache_key);
        if ($citys === false) {
            $citys = LbCity::find()
                ->where(['country_id' => $id])
                ->select(['id', 'city'])
                ->orderBy('city')
                ->asArray()
                ->all();
            $citys=json_encode($citys);
            $cache->set($cache_key, $citys);
        }
        //var_dump($citys);
        return $citys;
    }

}
