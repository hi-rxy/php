<?php
namespace backend\models\search;

use common\models\GoodsClass;

/**
 * 商品分类搜索模型
 * @package backend\models
 */
class GoodsClassSearch extends GoodsClass
{
    public $filters = [];

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
        if (isset($this->filters['name'])) $where[] = ['like', 'goods_class_.name', $this->filters['name']];
        if (!empty($this->filters['status'])) $where[] = ['goods_class_.status' => $this->filters['status']];
        // 左侧分类查询条件
        if ($this->filters) {
            if (isset($this->filters['pid']) && $this->filters['pid'] && $this->filters['id']) {
                $where[] = ['goods_class_.pid' => $this->filters['pid'],'goods_class_.id' => $this->filters['id']];
            } else if (isset($this->filters['id']) && ($this->filters['id'] || $this->filters['id'] == 0)) {
                $count = GoodsClass::find()->filterWhere(['pid' => $this->filters['id']])->count();
                if (!$count) {
                    $where[] = ['goods_class_.id' => $this->filters['id']];
                } else {
                    $where[] = ['goods_class_.pid' => $this->filters['id']];
                }
            } else {
                if ($this->filters['pid'] !== 'ALL') {
                    $where[] = ['goods_class_.pid' => $this->filters['pid']];
                }
            }
        } else {
            $where[] = ['goods_class_.pid' => 0];
        }
        return $where;
    }

    /**
     * 查询对象
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = self::find();
        $query->alias('goods_class_');
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
        foreach ($array as &$value)
        {
            $value['type_name'] = $value['goodsType']['name'];
            unset($value['goodsType']);
        }
        unset($value);
        return $array;
    }
}
