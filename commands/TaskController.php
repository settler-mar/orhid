<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\module\task\models\Task;
use app\modules\user\models\User;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TaskController extends Controller
{
  /**
   * Задать пароль пользователю
   */
  public function actionSetPass($email, $pass)
  {
    $user = User::find()
      ->where(['email' => $email])
      ->one();

    if (!$user) {
      echo 'Пользователь не найден' . "\n";
      exit;
    }


    $user->setPassword($pass);
    if (strlen($user->phone) < 3) {
      $user->phone = "+123456789012";
    }

    if ($user->save()) {
      echo 'Пароль изменен' . "\n";
      exit;
    } else {
      echo 'Ошибка изменения пароля' . "\n";
      ddd($user->errors);
    }
  }

  /**
   * Выполнение действий по расписанию
   */
  public function actionIndex()
  {
    //Смена тарифа
    $tasks = Task::find()
      ->andWhere(['task' => 1])
      ->andWhere(['<', 'add_time', time() + 600])// + 10 минут к текущему времени
      ->all();
    foreach ($tasks as $task) {
      $user = User::find()
        ->where(['id' => $task->user_id])
        ->one();

      $tarif = json_decode($task->params, true);
      $user->tariff_unit = $tarif['tariff_unit']; //задаем данные тарифа
      $user->tariff_id = $tarif['tariff_id']; //задаем текущий тариф
      $user->save();

      $task->delete(); //удаляем задачу
    }
  }
}
