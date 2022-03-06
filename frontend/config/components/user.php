<?php
// +----------------------------------------------------------------------
// | 用户设置
// +----------------------------------------------------------------------
return [
    'identityClass'     => 'common\models\User',
    'enableAutoLogin'   => true,
    'identityCookie'    => [
        'name'      => '_identity-frontend',
        'httpOnly'  => true
    ],
];