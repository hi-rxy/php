<?php
// +----------------------------------------------------------------------
// | 国际化设置
// +----------------------------------------------------------------------
return [
    'translations' => [
        'admin*' => [
            'class'     => 'yii\i18n\PhpMessageSource',
            'basePath'  => '@jinxing/admin/messages',
            'fileMap'   => [
                'admin' => 'admin.php',
            ],
        ],
        'error*' => [
            'class'     => 'yii\i18n\PhpMessageSource',
            'basePath'  => '@jinxing/admin/messages',
            'fileMap'   => [
                'error' => 'error.php',
            ],
        ],
    ],
];