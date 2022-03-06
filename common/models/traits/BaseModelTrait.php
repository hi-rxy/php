<?php
namespace common\models\traits;

use yii\db\Query;

trait BaseModelTrait
{
    /**
     * @param $table
     * @param array $where
     * @param array $field
     * @param array $order
     * @return Query
     */
    private static function searchObject ($table, $where = [], $field = [], $order = [])
    {
        $query = (new Query());
        $query->from($table);

        # 是否设置显示字段
        if (!empty($field)) $query->select($field);

        $where && $query->filterWhere($where);
        $order && $query->orderBy($order);
        return $query;
    }

    /**
     * 获取查询对象
     * @param $table
     * @param array $where
     * @param array $field
     * @param array $order
     * @return Query
     */
    public static function getQueryObject ($table, $where = [], $field = [], $order = [])
    {
        return self::searchObject($table,$where,$field,$order);
    }

    /**
     * 获取数据集合
     * @param $table
     * @param array $where
     * @param array $field
     * @param array $order
     * @return array
     */
    public static function getDataList ($table, $where = [], $field = [], $order = [])
    {
        return self::searchObject($table,$where,$field,$order)->all();
    }

    /**
     * 获取一条数据
     * @param $table
     * @param array $where
     * @param array $field
     * @param array $order
     * @return array|bool
     */
    public static function getDataOne ($table, $where = [], $field = [], $order = [])
    {
        return self::searchObject($table,$where,$field,$order)->one();
    }

    /**
     * 获取字段值
     * @param $table
     * @param array $where
     * @param array $field
     * @param array $order
     * @return array
     */
    public static function getDataValue ($table, $where = [], $field = [], $order = [])
    {
        return self::searchObject($table,$where,$field,$order)->column();
    }
}