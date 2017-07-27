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
        'js/photoswipe/PhotoSwipe/photoswipe.css',
        'js/photoswipe/PhotoSwipe/default-skin/default-skin.css',
        'css/site.css',
        'css/admin.css',
    ];
    public $js = [
        'js/photoswipe/PhotoSwipe/photoswipe.min.js',
        'js/photoswipe/PhotoSwipe/photoswipe-ui-default.min.js',
        'js/photoswipe/jqPhotoSwipe.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = [ 'position' => \yii\web\View::POS_HEAD ];
}
