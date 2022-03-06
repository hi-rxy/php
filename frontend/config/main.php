<?php
// +----------------------------------------------------------------------
// | APP设置
// +----------------------------------------------------------------------
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/site.php',
    require __DIR__ . '/params.php'
);

return [
    'id'                => 'app-frontend',
    'basePath'          => dirname(__DIR__),
    'bootstrap'         => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components'        => [
        'user'          => require __DIR__ . '/components/user.php',

        'log'           => require __DIR__ . '/components/log.php',

        'request'       => require __DIR__ . '/components/request.php',

        'session'       => require __DIR__ . '/components/session.php',

        'urlManager'    => require __DIR__ . '/components/url.php',

        'errorHandler'  => require __DIR__ . '/components/error.php',
    ],
    'params'        => $params,
];
