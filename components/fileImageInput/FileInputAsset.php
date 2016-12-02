<?php

namespace app\components\fileImageInput;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    /**
     * @inheritdoc
     */
    public function init()
    {
        //$this->css[] = 'css/___.css';
        //$this->js[] = 'js/_.js';
        parent::init();
    }
}