<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/admin.css',
        'css/kalendae.css'
    ];
    public $js = [
        'js/pagination.js',
        'js/ajaxfileupload.js',
        'js/admin.js',
        'js/mousewheel.js',
        'js/kalendae.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
