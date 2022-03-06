<?php

namespace backend\template\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@backend/web/static/default/';

    public $sourcePath = '@backend/web/static/default/';

    public $css = [
        'extends/AmazeUI-2.7.2/css/amazeui.css',
        'css/base.css',
        'extends/layui2.4.5/css/layui.css',
    ];

    public $js = [
        'extends/js/jquery-1.7.2.min.js',
        'js/common.js',
        'extends/AmazeUI-2.7.2/js/amazeui.min.js',
        'extends/layui2.4.5/layui.all.js'
    ];

    public $depends = [

    ];
}
