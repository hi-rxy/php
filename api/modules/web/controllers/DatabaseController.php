<?php
namespace api\modules\web\controllers;

use api\controllers\AuthController;
use api\models\web\Table;
use Yii;

class DatabaseController extends AuthController
{
    /**
     * @Notes: 查询
     * @Function: actionSearch
     * @return array
     * @throws \yii\db\Exception
     * @Author: 17908
     * @Time: 2022/3/2 0002 20:09
     */
    public function actionSearch()
    {
        $page = $this->request->get('page', 1);
        $rows = $this->request->get('limit', 10);
        $table = $this->request->get('table', '');
        $db      = Yii::$app->db->createCommand("SHOW TABLE STATUS");
        $list    = $db->queryAll();
        $index   = 0; // 默认多维数组索引

        // 表名搜索
        if ($table) {
            foreach ($list as $key => $table) {
                $table = array_values($table);
                if (in_array($table,$table)) {
                    $index = $key;
                }
            }
            $tables[] = $list[$index];
            $total    = 1;
        } else {
            $total  = count($list);
            $tables = $list;
        }

        $offset = ( $page - 1 ) * $rows;
        $params['items'] = array_slice($tables, $offset, $rows);
        $params['total'] = (int)$total;// 总数量
        $params['pageCount'] = (int)ceil(( $total / $rows ));// 总页数
        $params['pageSize'] = (int)$page;
        return $this->success($params);
    }

    /**
     * @Notes: 优化表
     * @Function: actionOptimizeTable
     * @return array
     * @throws \yii\db\Exception
     * @Author: 17908
     * @Time: 2022/3/2 0002 20:16
     */
    public function actionOptimizeTable ()
    {
        return $this->success(['result' => Table::optimize($this->request->post())]);
    }

    /**
     * @Notes: 修复表
     * @Function: actionRepairTable
     * @return array
     * @throws \yii\db\Exception
     * @Author: 17908
     * @Time: 2022/3/2 0002 20:22
     */
    public function actionRepairTable ()
    {
        return $this->success(['result' => Table::repair($this->request->post())]);
    }
}
