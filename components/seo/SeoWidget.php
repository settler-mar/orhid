<?php

namespace app\components\seo;

use app\modules\user\models\forms\LoginForm;
use app\modules\user\models\User;
use yii\base\Widget;
use yii\helpers\Url;
use Yii;
use app\modules\staticPages\models\StaticPages;

class SeoWidget extends Widget
{
  public function run()
  {
    $pathInfo = Yii::$app->request->getPathInfo();
    if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
      $model = StaticPages::find()->where(['url' => $matches[1]])->one();
      if ($model) {
        $arr = array(
          'meta_title' => "<title>".$model->meta_title."</title>",
          'keywords' => "<meta name='keywords' content=".$model->keywords." />",
          'description' => "<meta name='description' content=".$model->description."/>",
          'title' => $model->title,
          'text' => $model->text,
          'test' => '434343'
        );
        return json_encode($arr);
      }
      return '';
    }
    return '';
  }

}