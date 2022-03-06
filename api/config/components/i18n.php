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
                'admin'         => 'admin.php',
                'admin/model'   => 'model.php',
                'admin/error'   => 'error.php',
            ],
        ],
    ],
];