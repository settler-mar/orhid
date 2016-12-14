<?php

namespace app\controllers;

use app\models\LbCity;

class MessagesController extends \yii\web\Controller
{


    public function actionIndex()
    {
        return $this->render('index');
    }

}
