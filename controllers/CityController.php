<?php

namespace app\controllers;

use app\models\LbCity;

class CityController extends \yii\web\Controller
{
    public function actionGet($id = null)
    {
        $citys = LbCity::find()
            ->where(['country_id' => $id])
            ->select(['id', 'city','state'])
            ->orderBy('city')
            ->asArray()
            ->all();
        //var_dump($citys);
        return json_encode($citys);
    }

}
