<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2022/2/26
 * Time: 13:56
 */

return [
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'v1/site' ],
        'extraPatterns' => [
            'POST login' => 'login',
            'POST register' => 'register',
            'POST send-code' => 'send-code',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'v1/store' ],
        'extraPatterns' => [
            'POST apply' => 'apply',
            'GET result' => 'result',
            'GET index' => 'index',
            'GET seo' => 'seo',
            'POST check-store-name' => 'check-store-name',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/seller-store',
            'v1/seller-goods'
        ],
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'PUT,PATCH update' => 'update',
            'POST delete' => 'delete',
        ],
    ],
];