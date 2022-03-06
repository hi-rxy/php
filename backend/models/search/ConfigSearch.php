<?php
namespace backend\models\search;

use common\models\Config;

/**
 * 配置搜索模型
 * @package backend\models
 */
class ConfigSearch extends Config
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
        $where[] = ['group_id' => $this->filters['groupId']];
        if (isset($this->filters['name'])) $where[] = ['like', 'goods_.name', $this->filters['name']];
        if (isset($this->filters['keywords'])) $where[] = ['like', 'store_.name', $this->filters['keywords']];
        return $where;
    }

    /**
     * 查询对象
     * @param $filters
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = Config::find();
        $query->alias('config_');
        $query->filterWhere($this->queryConditions());
        return $query;
    }
}
