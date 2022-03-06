<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2022/2/22
 * Time: 13:25
 */

namespace api\models;

use api\models\web\traits\CurdModelTrait;

class BaseModel extends \yii\db\ActiveRecord
{
    use CurdModelTrait;

    /**
     * @Notes: 批量更新sql
     * @Function: batchUpdateSql
     * @param $table string 表名
     * @param $key  string 条件主键,作用参考switch中的case
     * @param $val  string 修改主键
     * @param $data array $key与$val主键对应的数据载体
     * @return string 批量更新SQL
     * @Author: 17908
     * @Time: 2022/3/5 0005 22:37
     */
    public function batchUpdateSql($table, $key, $val, $data)
    {
        $ids = implode(",", array_column($data, $key));
        $condition = " ";
        foreach ($data as $v){
            $condition .= "WHEN {$v[$key]} THEN '{$v[$val]}'";
        }
        $sql = "UPDATE `{$table}` SET  {$val} = CASE {$key} {$condition} END WHERE {$key} in ({$ids})";
        return $sql;
    }
}
