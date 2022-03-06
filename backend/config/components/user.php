<?php
// +----------------------------------------------------------------------
// | 用户设置
// +----------------------------------------------------------------------
return [
    'identityClass'     => 'jinxing\admin\models\Admin',
    'loginUrl'          => '/admin/default/login',
    'enableAutoLogin'   => true,
    'idParam'           => '_id_backend',
    'returnUrlParam'    => '_url_backend',
    'identityCookie'    => [
        'name'      => '_identity_backend',
        'httpOnly'  => true
    ],
];