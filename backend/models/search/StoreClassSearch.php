<?php
namespace backend\models\search;

use common\models\StoreClass;
use yii\db\ActiveQuery;

/**
 * 店铺分类搜索模型
 * @package backend\models
 */
class StoreClassSearch extends StoreClass
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

        if (isset($this->filters['name'])) $where[] = ['like', 'store_class_.name', $this->filters['name']];
        if (isset($this->filters['status'])) $where[] = ['store_class_.status' => $this->filters['status']];
        if ($this->filters) {
            if (isset($this->filters['pid']) && $this->filters['pid'] && $this->filters['id']) {
                $where[] = ['store_class_.pid' => $this->filters['pid'],'store_class_.id' => $this->filters['id']];
            } else if (isset($this->filters['id']) && ($this->filters['id'] || $this->filters['id'] == 0)) {
                $count = StoreClass::find()->filterWhere(['pid' => $this->filters['id']])->count();
                if (!$count) {
                    $where[] = ['store_class_.id' => $this->filters['id']];
                } else {
                    $where[] = ['store_class_.pid' => $this->filters['id']];
                }
            } else {
                if (isset($this->filters['store_id']) && $this->filters['store_id'] !== 'ALL') {
                    $where[] = ['store_class_.store_id' => $this->filters['store_id']];
                }
                if (isset($this->filters['pid']) && $this->filters['pid']) {
                    $where[] = ['store_class_.pid' => $this->filters['pid']];
                } else {
                    $where[] = ['store_class_.pid' => 0];
                }
            }
        } else {
            $where[] = ['store_class_.pid' => 0];
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
        $query->alias('store_class_');
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
            $value['storeId']        = $value['store']['id'];
            $value['store_id']       = $value['store']['name'];
            $value['create_user_id'] = $value['admin']['username'];
            $value['parentsId']      = empty($value['parents']) ? 0 : $value['parents']['id'];
            $value['pid']            = empty($value['parents']) ? '顶级分类' : $value['parents']['name'];
            unset($value['store'],$value['admin'],$value['parents']);
        }
        unset($value);
        return $array;
    }
}
