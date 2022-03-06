<?php
namespace backend\controllers;

use yii;

class ExportController extends DbController
{
    /**
     * 重写search方法
     * @return mixed|string|yii\web\Response
     * @throws yii\db\Exception
     */
    public function actionSearch()
    {
        $request = Yii::$app->request;
        $db      = Yii::$app->db->createCommand("SHOW TABLE STATUS");
        $list    = $db->queryAll();
        $index   = 0; // 默认多维数组索引

        // 表名搜索
        $filters = $request->get('filters',[]);
        if ($filters) {
            $name = $filters['Name'];
            foreach ($list as $key => $table) {
                $table = array_values($table);
                if (in_array($name,$table)) {
                    $index = $key;
                }
            }
            $tables[] = $list[$index];
            $total    = 1;
        } else {
            $total  = count($list);
            $tables = $list;
        }

        $orderBy    = trim($request->get('orderBy', ''));
        $offset     = intval($request->get('offset', ''));
        $limit      = intval($request->get('limit', ''));
        $draw       = intval($request->get('draw', 1));

        return $this->asJson([
            'draw' => [
                'draw'      => $draw,// 请求次数
                'orderBy'   => $orderBy,// 排序条件
                'offset'    => $offset, // 查询开始位置
                'limit'     => $limit, // 查询数据条数
                'filters'   => $filters,// 查询过滤条件
            ],
            'recordsTotal'      => $total, // 数据总条数
            'recordsFiltered'   => $total, // 数据总条数
            'data'              => array_slice($tables, $offset, $limit) // 数据信息
        ]);
    }
}