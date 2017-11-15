<?php

namespace app\commands;

use yii\console\Controller;
use Yii;

class TestController extends Controller
{

  /**
   * Тест почты. отправка письма на matuhinmax@mail.ru
   */
  public function actionMail()
  {
    try {

      \Yii::$app
        ->mailer
        ->compose()
        ->setFrom(['admin@example.com'])
        ->setSubject('Тема сообщения')
        ->setTextBody('Текст сообщения')
        ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
        ->setTo('matuhinmax@mail.ru')
        ->setSubject(Yii::$app->name . ': Тест')
        ->send();

 /*     \Yii::$app
        ->mailer
        ->compose()
        ->setSubject('Тема сообщения')
        ->setTextBody('Текст сообщения')
        ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
        ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->params['adminEmail']])
        ->setTo('matuhinmax@mail.ru')
        ->setSubject(Yii::$app->name . ': Тест')
        ->send();*/
    } catch (\Exception $e) {
      ddd($e);
      echo  'error';
    }
  }
}