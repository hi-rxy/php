<?php
namespace backend\models\search;

use common\models\Category;

/**
 * 文章分类搜索模型
 * @package backend\models
 */
class CategorySearch extends Category
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
     * @param $filters
     * @return string[]
     */
    private function queryConditions ()
    {
        $where = ['and'];
        // 右侧数据表格查询条件
        if (isset($this->filters['name'])) $where[] = ['like', 'name', $this->filters['name']];
        if (isset($this->filters['parent_id']) && $this->filters['parent_id'] != 'ALL') $where[] = ['like', 'parent_id', $this->filters['parent_id']];
        // 左侧分类查询条件
        if ($this->filters) {
            if ($this->filters['parent_id'] == "ALL") $where[] = ['parent_id' => 0];

            if ($this->filters['parent_id'] && $this->filters['id']) {
                $where[] = ['parent_id' => $this->filters['parent_id'],'id' => $this->filters['id']];
            } else if ($this->filters['id'] || $this->filters['id'] == 0) {
                if (!$this->isParentId($this->filters['id'])) {
                    $where[] = ['parent_id' => $this->filters['id']];
                } else {
                    $where[] = ['id' => $this->filters['id']];
                }
            }
        } else {
            $where[] = ['parent_id' => 0];
        }
        return $where;
    }

    /**
     * 是否是父级分类
     * @param $id
     * @return bool
     */
    private function isParentId ($id)
    {
        $result = self::findOne($id);
        return $result['parent_id'] ? true : false;
    }

    /**
     * 查询对象
     * @param $filters
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = self::find();
        $query->filterWhere($this->queryConditions());
        return $query;
    }
}
