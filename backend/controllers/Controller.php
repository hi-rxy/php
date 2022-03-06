<?php

namespace backend\controllers;

use common\helpers\Logs;
use jinxing\admin\behaviors\Logging;
use jinxing\admin\controllers\Controller as BaseController;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yiier\humansLog\HLogBehavior;

/**
 * Class Controller 后台的基础控制器
 * @author  liujx
 * @package backend\controllers
 */
class Controller extends BaseController
{
    /**
     * @var string 使用 yii2-admin 的布局
     */
    public $layout = '@jinxing/admin/views/layouts/main';

    /**
     * @var string 使用自己定义的上传文件处理表单
     */
    public $uploadFromClass = 'backend\models\forms\UploadForm';

    /**
     * @var array 缓存名称
     */
    public $redisKey = [
        'WEB_GLOBAL_CONFIG'
    ];

    /** @var $logs Logs */
    protected $logs;

    /**
     * 定义使用的行为
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'       => true,
                        //'permissions' => [$this->action->getUniqueId()],
                        'permissions' => ['@'],
                    ],
                ],
            ],

            'logging' => [
                'class' => Logging::className(),
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->logs = self::logs('./logs/',date('YmdH').'.log');
        try {
            return parent::beforeAction($action);
        } catch (BadRequestHttpException $e) {
            return $e->getMessage();
        }
    }

    // 设置日志路径
    public static function logs($path = './logs/', $log_file = 'default.log')
    {
        $config = [
            'default' => [
                'log_path' => $path,        // 日志根目录
                'log_file' => $log_file,       // 日志文件
                'format' => 'Y-m-d',         // 日志自定义目录，使用日期时间定义
            ]
        ];
        Logs::set_config($config);
        return Logs::get_logger();
    }
}
