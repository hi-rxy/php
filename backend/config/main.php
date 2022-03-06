<?php
// +----------------------------------------------------------------------
// | APP设置
// +----------------------------------------------------------------------
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/site.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/error-code.php'
);

return [
    'id'                => 'backend',
    'name'              => '商城后台',
    'basePath'          => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'         => ['log'],
    'language'          => 'zh-CN',//默认语言
    'modules'           => require __DIR__ . '/components/modules.php',
    'components'        => [
        'user'          => require __DIR__ . '/components/user.php',
        'log'           => require __DIR__ . '/components/log.php',
        'assetManager'  => require __DIR__ . '/components/asset.php',
        'i18n'          => require __DIR__ . '/components/i18n.php',
        'request'       => require __DIR__ . '/components/request.php',
        'session'       => require __DIR__ . '/components/session.php',
        'urlManager'    => require __DIR__ . '/components/url.php',
        'authManager'   => require __DIR__ . '/components/auth.php',
        'errorHandler'  => require __DIR__ . '/components/error.php',
    ],
    'on beforeRequest'  => [\common\components\Config::className(), 'backendInit'],
    'params'            => $params,
];
