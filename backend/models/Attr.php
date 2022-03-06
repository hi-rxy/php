<?php
namespace backend\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%attr}}".
 *
 * @property int $id 属性id
 * @property int $type_id 类型id
 * @property string $name 属性名称
 * @property string $value 属性值
 * @property string $unit 属性单位
 * @property int $search 是否支持搜索0表示支持1表示支持
 * @property int $type 控件类型1单选2多选3下拉4复选框
 * @property int $sort 控件排序
 * @property int $status 开启状态0表示关闭1表示开启
 * @property int $style 0表示参数1表示规格
 */
class Attr extends \common\models\Attr
{
    const STATUS_CLOSE = 0; // 关闭
    const STATUS_OPEN = 1; // 开启

    /**
     * 检查用户POST的属性值 是新增、修改还是删除 通过POST值带有attr_id判断
     * @param array $post
     * @return array|mixed|string
     */
    public static function handlerPostAttrs($post = [])
    {
        $attrs   = isset($post['attrs']) ? $post['attrs'] : [];
        $attrIds = isset($attrs['attr_id']) ? $attrs['attr_id'] : []; //属性ID
        $names   = isset($attrs['name']) ? $attrs['name'] : [];//参数名称
        $styles  = isset($attrs['style']) ? $attrs['style'] : [];//参数类型
        $types   = isset($attrs['type']) ? $attrs['type'] : [];//显示方式
        $params  = isset($attrs['value']) ? $attrs['value'] : [];//参数值
        $units   = isset($attrs['unit']) ? $attrs['unit'] : [];//参数单位
        $search  = isset($attrs['search']) ? $attrs['search'] : [];//是否参与筛选
        $sorts   = isset($attrs['sort']) ? $attrs['sort'] : [];//排序
        $update  = $create = [];

        foreach ($names as $key => $name) 
        {
            if (empty($name) || empty($params[$key])) return false;

            $value[0] = $post['typeId']; //类型ID
            $value[1] = $name;//参数名称
            $value[2] = $styles[$key];//参数类型
            $value[3] = $types[$key];//参数显示方式
            $value[4] = $params[$key];//参数值
            $value[5] = (empty($units) ? '' : $units[$key]);//参数单位
            $value[6] = (empty($search) ? 0 : (isset($search[$key]) ? 1 : 0));//是否参与筛选
            $value[7] = (empty($sorts) ? 1 : $sorts[$key]);//排序
            if ($attrIds[$key]) {
                $value[8] = $attrIds[$key];
                $update[] = $value;
            } else {
                unset($value[8]);
                $create[] = $value;
            }
        }

        return [$create,$update];
    }

    /**
     * 处理添加事件
     * @return bool|int
     * @throws yii\db\Exception
     */
    public static function batchInsertAttrs($data)
    {
        if (empty($data)) return false;
        $command = Yii::$app->db->createCommand();
        $columns = [
            'type_id',
            'name',
            'style',
            'type',
            'value',
            'unit',
            'search',
            'sort'
        ];
        return $command->batchInsert(self::tableName(), $columns, $data)->execute();
    }

    /**
     * 处理批量修改事件
     * @return bool|int
     * @throws yii\db\Exception
     */
    public static function batchUpdateAttrs($data)
    {
        if (empty($data)) return false;
        $sql = '';
        foreach ($data as $key => $item) {
            $sql .= "UPDATE " . self::tableName() . " SET
                    `type_id` = '$item[0]',
                    `name` = '$item[1]',
                    `style` = '$item[2]',
                    `type` = '$item[3]',
                    `value` = '$item[4]',
                    `unit` = '$item[5]',
                    `search` = '$item[6]',
                    `sort` = '$item[7]'
                     WHERE `id` = '$item[8]';";
        }
        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * 处理批量删除事件
     * @return int
     */
    public static function batchDeleteAttrs($data)
    {
        return Attr::deleteAll(['id' => $data]);
    }
}
