<?php
// +----------------------------------------------------------------------
// | 全局请求设置
// +----------------------------------------------------------------------
return [
    'csrfParam'             => '_csrf_api',
    'cookieValidationKey'   => 's2LK6wSyyrvMAyJUHBOJn2RRrThtIQ7E',
    'parsers'       => [
        'application/json'  => 'yii\web\JsonParser',
        'text/json'         => 'yii\web\JsonParser',
    ],
];
