<?php
// +----------------------------------------------------------------------
// | 系统日志设置
// +----------------------------------------------------------------------
return [
    'traceLevel'        => YII_DEBUG ? 3 : 0,
    'targets'   => [
        [
            'class'     => 'yii\log\FileTarget',
            'levels'    => ['error',/*'info','trace',*/'warning'],
            'logVars'   => ['_GET', '_POST', '_FILES',],
        ],
    ],
];
