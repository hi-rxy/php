<?php
// +----------------------------------------------------------------------
// | 资源设置
// +----------------------------------------------------------------------
return [
    'appendTimestamp'       => true,
    'bundles'       => [
        // 去掉自己的bootstrap 资源
        'yii\bootstrap\BootstrapAsset' => [
            'css'           => [],
        ],
        // 去掉自己加载的Jquery
        'yii\web\JqueryAsset' => [
            'sourcePath' => null,
            'js'            => [],
        ],
    ],
];
