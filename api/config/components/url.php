<?php
// +----------------------------------------------------------------------
// | URL地址美化设置
// +----------------------------------------------------------------------
# common
$route['common'] = [
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'common' ],
        'extraPatterns' => [
            'POST upload' => 'upload',
            'POST delete' => 'delete-image',
        ],
    ],
];
# v1
$route['v1'] = require CONFIG_ROUTES.'/v1.php';;
# web
$route['web'] = require CONFIG_ROUTES.'/web.php';

$config['enablePrettyUrl'] = true;
$config['showScriptName'] = false;
$config['enableStrictParsing'] = true;
$config['rules'] = array_merge($route['common'],$route['v1'],$route['web']);
return $config;
