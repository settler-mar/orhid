<?php
/**
 * Created by PhpStorm.
 * User: Tolik
 * Date: 18.06.2017
 * Time: 17:18
 */

namespace app\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;


class staticPagesRules extends Object implements UrlRuleInterface
{
  public function createUrl($manager, $route, $params)
  {
    if ($route === 'car/index') {
      if (isset($params['manufacturer'], $params['model'])) {
        return $params['manufacturer'] . '/' . $params['model'];
      } elseif (isset($params['manufacturer'])) {
        return $params['manufacturer'];
      }
    }
    return false;  // данное правило не применимо
  }

  public function parseRequest($manager, $request)
  {
    $pathInfo = $request->getPathInfo();
    if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
      // Ищем совпадения $matches[1] и $matches[3]
      // с данными manufacturer и model в базе данных
      // Если нашли, устанавливаем $params['manufacturer'] и/или $params['model']
      // и возвращаем ['car/index', $params]
    }
    return false;  // данное правило не применимо
  }
}