<?php

namespace backend\controllers;

use common\helpers\Database;
use yii;

/**
 * Class DbController 数据库管理 执行操作控制器
 * @package backend\controllers
 */
class DbController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = '';

    /** @var array 定义备份配置 */
    public $config = [];

    public function beforeAction($action)
    {
        $this->config = [
            'DB_PATH_NAME' => Yii::$app->params['site']['BACKUP_PATH_NAME'],     //备份目录名称,主要是为了创建备份目录；
            'DB_PATH' => Yii::$app->params['site']['BACKUP_PATH'],      //数据库备份路径必须以 / 结尾；
            'DB_PART' => Yii::$app->params['site']['PART_SIZE'],        //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
            'DB_COMPRESS' => Yii::$app->params['site']['COMPRESS'],     //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
            'DB_LEVEL' => Yii::$app->params['site']['COMPRESS_LEVEL'],  //压缩级别   1:普通   4:一般   9:最高
        ];
        return parent::beforeAction($action);
    }

    /**
     * 优化表
     * @return mixed|string
     * @throws yii\db\Exception
     */
    public function actionOptimize()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            // 检查参数是否正确
            if (empty($post['name'])) return $this->error(201, '请指定要优化的表');

            // 转数据的方式处理
            $tables = [];
            if (!is_array($post['name'])) {
                array_push($tables, $post['name']);
            } else {
                $tables = $post['name'];
            }

            // 执行优化表命令
            $tablestr = implode('`,`', $tables);
            $db = Yii::$app->db->createCommand("OPTIMIZE TABLE `{$tablestr}`");

            // 判断执行结果
            if (!$result = $db->execute()) return $this->error(201, "数据表`{$tablestr}`优化出错请重试!");

            return $this->success([], "数据表`{$tablestr}`优化完成!");
        }

        return $this->returnJson();
    }

    /**
     * 修复表
     * @return mixed|string
     * @throws yii\db\Exception
     */
    public function actionRepair()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            // 检查参数是否正确
            if (empty($post['name'])) return $this->error(201, '请指定要修复的表');

            // 转数据的方式处理
            $tables = [];
            if (!is_array($post['name'])) {
                array_push($tables, $post['name']);
            } else {
                $tables = $post['name'];
            }

            // 执行优化表命令
            $tablestr = implode('`,`', $tables);
            $db = Yii::$app->db->createCommand("REPAIR TABLE `{$tablestr}`");

            // 判断执行结果
            if (!$result = $db->execute()) return $this->error(201, "数据表`{$tablestr}`修复出错请重试!");

            return $this->success([], "数据表`{$tablestr}`修复完成!");
        }

        return $this->returnJson();
    }

    /**
     * 备份表
     * @return mixed|string
     */
    public function actionBackup()
    {
        $redisKeyConfig = 'db_backup_config'; // 缓存配置
        $redisKeyFiles  = 'db_backup_files'; // 缓存生成的备份文件信息
        $redisKeyTables = 'db_backup_tables'; // 缓存mysql表名称

        $tables = Yii::$app->request->post('tables', []);
        $id     = Yii::$app->request->get('id',0);
        $start  = Yii::$app->request->get('start',0);

        //读取备份配置
        $config = array(
            'path' => $this->config['DB_PATH'],
            'part' => $this->config['DB_PART'],
            'compress' => $this->config['DB_COMPRESS'],
            'level' => $this->config['DB_LEVEL'],
        );

        if (Yii::$app->request->isPost) {
            if (empty($tables)) return $this->error(201, '请选择需要备份的数据库表');

            //检查是否有正在执行的任务 创建文件锁
            $lock = "{$config['path']}backup.lock";

            if (is_file($lock)) {
                return $this->error(201, '检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, $_SERVER['REQUEST_TIME']);
            }

            //检查备份目录是否可写 创建备份目录
            $path = $config['path'] . $this->config["DB_PATH_NAME"] . '/';
            is_writable($config['path']) || mkdir($path, 0777, true);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', time()),
                'part' => 1,
            );

            // 缓存配置
            $expire = time() + 300;
            Yii::$app->cache->set($redisKeyConfig, $config, $expire);
            Yii::$app->cache->set($redisKeyFiles, $file,$expire);
            Yii::$app->cache->set($redisKeyTables, $tables, $expire);

            $db = new Database($file, $config);
            if (false !== $db->create()) {
                $tab = array('id' => 0, 'start' => 0);
                return $this->success(array('tables' => $tables, 'tab' => $tab), '初始化成功！');
            }

            return $this->error(201, '初始化失败，备份文件创建失败！');

        } else if (Yii::$app->request->isGet) {
            // 获取缓存
            $tables  = Yii::$app->cache->get($redisKeyTables);
            $file    = Yii::$app->cache->get($redisKeyFiles);
            $config  = Yii::$app->cache->get($redisKeyConfig);

            $start   = (new Database($file, $config))->backup($tables[$id], $start);
            if (false === $start) { //出错
                return $this->error(201, '备份出错');
            }

            if (0 === $start) { //下一表
                if (isset($tables[++$id])) {
                    $tab = array('id' => $id, 'start' => 0);
                    return $this->success(array('tab' => $tab), '备份完成！');
                } else {
                    // 删除锁文件
                    unlink($config['path'] . 'backup.lock');

                    // 删除缓存
                    Yii::$app->cache->delete($redisKeyTables);
                    Yii::$app->cache->delete($redisKeyFiles);
                    Yii::$app->cache->delete($redisKeyConfig);

                    return $this->success('备份完成！');
                }
            } else { // 数据大于1000
                $tab = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                return $this->success(array('tab' => $tab), "正在备份...({$rate}%)");
            }
        } else {//出错
            return $this->error(201, '参数错误！');
        }
    }

    /**
     * 导入表
     * @return mixed|string
     */
    public function actionImport()
    {
        $redisKeyName = 'db_import_files'; // 缓存键名称

        $title = Yii::$app->request->post('title', '');
        $part  = Yii::$app->request->get('part', 1);
        $start = Yii::$app->request->get('start', 0);

        if (Yii::$app->request->isPost) { //初始化
            if (!$title) return $this->error(201, '参数错误！');

            // 检索文件
            $files = $title . '-*.sql.*';
            $filepath = realpath($this->config['DB_PATH']) . DIRECTORY_SEPARATOR . $files;
            $fileinfo = glob($filepath);

            //检测文件正确性
            $data = array();
            foreach ($fileinfo as $file) {
                $basename = basename($file);
                $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $data[$match[6]] = array($match[6], $file, $gz);
            }
            ksort($data);
            $end = end($data);

            if (count($data) === $end[0]) {
                // 缓存备份列表
                Yii::$app->cache->set($redisKeyName,$data, time() + 300);

                return $this->success(array('part' => 1, 'start' => 0), '初始化完成!');
            } else {
                return $this->error(201,'备份文件可能已经损坏，请检查！');
            }
        } else if (Yii::$app->request->isGet) {
            // 获取缓存文件信息
            $files = Yii::$app->cache->get($redisKeyName);

            // 配置信息
            $config = [
                'path' => realpath($this->config['DB_PATH']) . DIRECTORY_SEPARATOR,
                'compress' => $files[$part][2]
            ];

            $start  = (new Database($files[$part], $config))->import($start);
            if (false === $start) {
                return $this->error(201,'还原数据出错！');

            } else if (0 === $start) { //下一卷
                if (isset($list[++$part])) {
                    $data = array('part' => $part, 'start' => 0);
                    return $this->success($data, "正在还原...#{$part}");
                } else {
                    // 删除缓存数据
                    Yii::$app->cache->delete($redisKeyName);

                    return $this->success([],'还原完成！');
                }

            } else {
                $data = array(
                    'part' => $part,
                    'start' => $start[0]
                );
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    return $this->success($data, "正在还原...#{$part} ({$rate}%)");
                } else {
                    $data['gz'] = 1;
                    return $this->success($data, "正在还原...#{$part}");
                }
            }
        }
    }

    /**
     * 文件下载
     * @return mixed|string|yii\console\Response|yii\web\Response
     */
    public function actionDownload()
    {
        $title = Yii::$app->request->post('title', '');
        if (!$title) {
            return $this->error(201, '参数错误！');
        }

        $name = $title . '-*.sql.*';
        $path = realpath($this->config['DB_PATH']) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        $filePath = $files[0];
        $arr = explode('\\', $filePath);
        $saveAsFileName = end($arr);

        return Yii::$app->response->sendFile($filePath, $saveAsFileName);
    }
}
