<?php
// +----------------------------------------------------------------------
// | APP设置
// +----------------------------------------------------------------------
return [
    'aliases'       => [
        '@bower'    => '@vendor/bower-asset',
        '@npm'      => '@vendor/npm-asset',
    ],
    'vendorPath'    => dirname(dirname(__DIR__)) . '/vendor',
    'components'    => [
        'cache'     => require __DIR__ . '/components/cache.php',
        'redis'     => require __DIR__ . '/components/redis.php',
        'db'        => require __DIR__ . '/components/db.php',
    ],
];
