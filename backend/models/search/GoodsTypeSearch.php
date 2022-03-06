<?php
namespace backend\models\search;

use common\models\GoodsType;

/**
 * 商品类型搜索模型
 * @package backend\models
 */
class GoodsTypeSearch extends GoodsType
{
    public $filters = [];

    private $styleParams = ['参数', '规格'];

    private $typeParams = ['单选框', '复选框', '输入框', '下拉框'];

    /**
     * 查询入口
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function search ($params)
    {
        $this->filters = $params;
        return $this->queryObject();
    }

    /**
     * 查询条件
     * @return string[]
     */
    private function queryConditions ()
    {
        $where = ['and'];
        if (isset($this->filters['name']))    $where[] = ['like', 'goods_type_.name', $this->filters['name']];
        if (isset($this->filters['status']))  $where[] = ['goods_type_.status' => $this->filters['status']];
        return $where;
    }

    /**
     * 查询对象
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = self::find();
        $query->alias('goods_type_');
        $query->filterWhere($this->queryConditions());
        return $query;
    }

    /**
     * 查询后的数据修改
     * @param $array
     * @return mixed
     */
    public function afterSearch (&$array)
    {
        $str   = '';
        foreach ($array as &$value)
        {
            if ($value['attr']) {
                $str = '';
                foreach ($value['attr'] as $item) {
                    $str .= '<font style="font-weight: bold">' . $item['name'] . '</font>&nbsp;&nbsp;&nbsp;&nbsp;[' . $this->styleParams[$item['style']] . ']&nbsp;&nbsp;&nbsp;&nbsp;[<font>' . $item['value'] . '</font>](' . $this->typeParams[$item['type'] - 1] . ')<br/>';
                }
            }
            $value['attr_value'] = $str;
            unset($value['attr']);
        }
        unset($value);
        return $array;
    }
}
