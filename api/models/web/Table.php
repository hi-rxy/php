<?php

namespace api\models\web;

use api\exceptions\HttpBadRequestException;
use Yii;
use yii\db\ActiveRecord;

class Table extends ActiveRecord
{
    /**
     * @Notes: 优化表
     * @Function: optimize
     * @param $post
     * @return int
     * @throws \yii\db\Exception
     * @Author: 17908
     * @Time: 2022/3/2 0002 20:14
     */
    public static function optimize ($post)
    {
        if (empty($post['name'])) throw new HttpBadRequestException('请指定要优化的表');
        // 转数据的方式处理
        $tables = [];
        if (!is_array($post['name'])) {
            array_push($tables, $post['name']);
        } else {
            $tables = $post['name'];
        }
        // 执行优化表命令
        $tables = implode('`,`', $tables);
        $db = Yii::$app->db->createCommand("OPTIMIZE TABLE `{$tables}`");
        if (!$db->execute()) throw new HttpBadRequestException("数据表`{$tables}`优化出错请重试!");
        return "数据表`{$tables}`优化完成!";
    }

    /**
     * @Notes: 修复表
     * @Function: repair
     * @param $post
     * @return string
     * @throws \api\exceptions\HttpBadRequestException
     * @throws \yii\db\Exception
     * @Author: 17908
     * @Time: 2022/3/2 0002 20:22
     */
    public static function repair ($post)
    {
        if (empty($post['name'])) throw new HttpBadRequestException('请指定要修复的表');
        // 转数据的方式处理
        $tables = [];
        if (!is_array($post['name'])) {
            array_push($tables, $post['name']);
        } else {
            $tables = $post['name'];
        }
        // 执行优化表命令
        $tables = implode('`,`', $tables);
        $db = Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`");
        if (!$db->execute()) throw new HttpBadRequestException("数据表`{$tables}`修复出错请重试!");
        return "数据表`{$tables}`修复完成!";
    }
}
