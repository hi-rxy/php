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
        'controller' => [ 'web/site' ],
        'extraPatterns' => [
            'POST admin-login' => 'login',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/admin' ],
        'extraPatterns' => [
            'GET admin-info' => 'admin',
            'POST admin-logout' => 'logout',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/menu' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'GET trees' => 'tree-menu',
            'POST role-menu' => 'menu-by-role',
            'GET detail' => 'view',
            'POST add' => 'create',
            'PUT edit' => 'update',
            'DELETE del' => 'delete',
            'PUT state' => 'state',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/admin' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'GET detail' => 'view',
            'GET roles' => 'get-roles',
            'POST add' => 'create',
            'PUT edit' => 'update',
            'DELETE del' => 'delete',
            'PUT state' => 'state',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/role' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'GET auth' => 'assign-permissions',
            'POST add' => 'create',
            'POST save-role-permission' => 'set-permission-by-role',
            'PUT edit' => 'update',
            'DELETE del' => 'delete',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/authority' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'POST add' => 'create',
            'PUT edit' => 'update',
            'DELETE del' => 'delete',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/auth-assignment' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'POST add' => 'create',
            'PUT edit' => 'update',
            'DELETE del' => 'delete',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/database' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'POST optimize-table' => 'optimize-table',
            'POST repair-table' => 'repair-table',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/config-group' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'GET detail' => 'view',
            'POST add' => 'create',
            'PUT edit' => 'update',
            'DELETE del' => 'delete',
        ],
    ],
    [
        'pluralize' => false,
        'class' => 'yii\rest\UrlRule',
        'controller' => [ 'web/configs' ],
        'extraPatterns' => [
            'GET list' => 'search',
            'GET fields' => 'set-configs',
            'POST add' => 'create',
            'PUT edit' => 'update',
            'PUT save-configs-fields' => 'update-configs',
            'DELETE del' => 'delete',
        ],
    ],
];
