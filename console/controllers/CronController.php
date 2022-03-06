<?php
namespace console\controllers;

use common\helpers\CronParser;
use yii;
use yii\console\ExitCode;

/**
 * CentOS 执行控制台程序
 * 注意：
 * ./yii cron/index
 * -bash: ./yii: 权限不够
 * 在当前目录下修改yii文件执行权限
 * chmod 777 yii
 *
 * 执行 crontab -e
 * 写入：* * * * * cd /home/web/yii2/ && /usr/local/php7/bin/php ./yii cron/index
 *
 * Class CronController
 * @package console\controllers
 */
class CronController extends Controller
{
    /**
     * 定时任务入口
     * @return int
     * @throws \Exception
     */
    public function actionIndex()
    {
        $this->logs->info(time());
//        $crontab = Task::find()->all();
//        if (!empty($crontab)){
//            $tasks = [];
//            foreach ($crontab as $task) {
//                // 判断运行时间到了没
//                if ($task['last_runtime'] <= time()) {
//                    $tasks[] = $task;
//                }
//            }
//            $this->executeTask($tasks);
//        }
//        return ExitCode::OK;
    }

    /**
     * @param  array $tasks 任务列表
     * @throws \Exception
     */
    private function executeTask(Array $tasks)
    {
        $pool = [];
        foreach ($tasks as $task) {
            $pool[] = proc_open("php yii task/index $task->id", [], $pipe);
        }

        // 回收子进程
        while (count($pool)) {
            foreach ($pool as $i => $result) {
                $etat = proc_get_status($result);
                if($etat['running'] == FALSE) {
                    proc_close($result);
                    unset($pool[$i]);
                    # 记录任务状态
                    $tasks[$i]->crontab && $tasks[$i]->last_runtime = strtotime($this->getNextRunDate($tasks[$i]->crontab));
                    // 任务出错
                    if ($etat['exitcode'] !== ExitCode::OK) {
                        //$tasks[$i]->status = 1;
                    }
                    $tasks[$i]->save(false);
                }
            }
        }
    }

    /**
     * 计算下次运行时间
     * @param $expression
     * @return bool
     * @throws \Exception
     */
    private function getNextRunDate($expression)
    {
        if (!CronParser::check($expression)) return false;
        return CronParser::formatToDate($expression, 1)[0];
    }
}