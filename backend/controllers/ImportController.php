<?php
namespace backend\controllers;

use yii;

class ImportController extends DbController
{
    /**
     * 重写search方法
     * @return mixed|string|yii\web\Response
     * @throws yii\db\Exception
     */
    public function actionSearch()
    {
        // 获取真实路径
        $path = realpath($this->config['DB_PATH']);
        // 使用迭代器 获取备份目录下所有文件 FilesystemIterator
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $files = new \FilesystemIterator($path, $flag);

        $list = $info = array();
        foreach ($files as $name => $item) {
            if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];

                if (isset($list["{$date} {$time}"])) {
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $item->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $item->getSize();
                }
                $extension = strtoupper(pathinfo($item->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time'] = strtotime("{$date} {$time}");
                $info['format_time'] = "{$date} {$time}";
                $info['title'] = date('Ymd-His',$info['time']);
                $list[] = $info;
                krsort($list);
            }
        }

        $request    = Yii::$app->request;
        $total      = count($list);
        $orderBy    = trim($request->get('orderBy', ''));
        $offset     = intval($request->get('offset', ''));
        $limit      = intval($request->get('limit', ''));
        return $this->asJson([
            'draw' => [
                'draw'      => $request->get('draw', 1),// 请求次数
                'orderBy'   => $orderBy,// 排序条件
                'offset'    => $offset, // 查询开始位置
                'limit'     => $limit, // 查询数据条数
                'filters'   => $request->get('filters'),// 查询过滤条件
            ],
            'recordsTotal'      => $total, // 数据总条数
            'recordsFiltered'   => $total, // 数据总条数
            'data'              => array_slice($list, $offset, $limit), // 数据信息
        ]);
    }

    /**
     * 删除备份文件
     * @return mixed|string
     */
    public function actionDelete()
    {
        if (!Yii::$app->request->isPost) {
            return $this->returnJson();
        }

        $title = Yii::$app->request->post('title', '');
        if (!$title) {
            return $this->error(201, '参数错误！');
        }

        $files = $title . '-*.sql.*';
        $path = realpath($this->config['DB_PATH']) . DIRECTORY_SEPARATOR . $files;
        array_map("unlink", glob($path));
        if (count(glob($path))) {
            return $this->error(201,'备份文件删除失败，请检查权限！');
        }

        return $this->success([],'备份文件删除成功！');
    }
}