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
use app\modules\staticPages\models\StaticPages;


class staticPagesRules extends Object implements UrlRuleInterface{

  /**
   * Creates a URL according to the given route and parameters.
   * @param UrlManager $manager the URL manager
   * @param string $route the route. It should not have slashes at the beginning or the end.
   * @param array $params the parameters
   * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
   */
  public function createUrl($manager, $route, $params)
  {
    return false;
  }

  public function parseRequest($manager, $request)
  {
    $pathInfo = $request->getPathInfo();
    if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
      $model = StaticPages::find()->where(['url'=>$matches[1]])->one();
      if ($model){
        return ['staticPages/default/show', ['title'=>$model->title, 'text'=>$model->text]];
      }
      // Ищем совпадения $matches[1] и $matches[3]
      // с данными manufacturer и model в базе данных
      // Если нашли, устанавливаем $params['manufacturer'] и/или $params['model']
      // и возвращаем ['car/index', $params]
    }
    return false;  // данное правило не применимо
  }
}