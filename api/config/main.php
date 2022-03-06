<?php

define('CONFIG_COMPONENTS',__DIR__ . '/components');
define('CONFIG_ROUTES',__DIR__ . '/routes');

$params = array_merge(
    require COMMON . '/config/params.php',
    require COMMON . '/config/site.php',
    require __DIR__ . '/params.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [ 'log' ],
    'timeZone' => 'Asia/Shanghai',
    'modules' => require CONFIG_COMPONENTS . '/modules.php',
    'components' => [
        'user' => require CONFIG_COMPONENTS . '/user.php',
        'log' => require CONFIG_COMPONENTS . '/log.php',
        'assetManager' => require CONFIG_COMPONENTS . '/asset.php',
        'response' => require CONFIG_COMPONENTS . '/response.php',
        'request' => require CONFIG_COMPONENTS . '/request.php',
        'session' => require CONFIG_COMPONENTS . '/session.php',
        'urlManager' => require CONFIG_COMPONENTS . '/url.php',
        'errorHandler' => require CONFIG_COMPONENTS . '/error.php',
        'mailer' => require CONFIG_COMPONENTS . '/mailer.php',
        'cache' => require CONFIG_COMPONENTS . '/cache.php',
        'authManager' => require CONFIG_COMPONENTS . '/auth.php',
    ],
    'as cors' => [
        'class' => \yii\filters\Cors::className(),
        'cors' => [
            'Origin' => [ '*' ],
            'Access-Control-Request-Method' => [ 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS' ],
            'Access-Control-Request-Headers' => [ '*' ],
        ],
    ],
    'params' => $params,
];
