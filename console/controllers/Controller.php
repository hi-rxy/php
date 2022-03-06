<?php
namespace console\controllers;

use common\helpers\Logs;

class Controller extends \yii\console\Controller
{
    /** @var $logs Logs */
    protected $logs;

    public function beforeAction($action)
    {
        $this->logs = self::logs('./console/cron/',date('YmdH').'.log');
        return parent::beforeAction($action);
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