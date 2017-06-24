<?php

namespace app\components\emojione;


class Asset extends \yii\web\AssetBundle
{
  /**
   * @inheritdoc
   */
  public $sourcePath = '@app/components/emojione/assets';

  public $depends = [
    'yii\web\JqueryAsset',
    //'app\components\emojione\EmojiOneAsset',
  ];

  /**
   * @inheritdoc
   */
  public function init()
  {
    $this->js = [!YII_DEBUG ? 'emojionearea.min.js' : 'emojionearea.js'];
    $this->css = [!YII_DEBUG ? 'emojionearea.min.css' : 'emojionearea.css'];

    parent::init();
  }
}