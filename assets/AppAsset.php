<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/fonts.css',
        'js/fancybox/jquery.fancybox.css',
        'css/site.css',
        'css/admin.css',
    ];
    public $js = [
        'js/fancybox/jquery.fancybox.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = [ 'position' => \yii\web\View::POS_HEAD ];
}
