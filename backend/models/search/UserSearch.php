<?php
namespace backend\models\search;

use common\models\User;

/**
 * 用户搜索模型
 * @package backend\models
 */
class UserSearch extends User
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
    private function queryCondition ()
    {
        $where = ['and'];
        if (isset($this->filters['username']))    $where[] = ['like', 'username', $this->filters['username']];
        if (isset($this->filters['mobile']))      $where[] = ['like', 'mobile', $this->filters['mobile']];
        if (isset($this->filters['email']))       $where[] = ['like', 'email', $this->filters['email']];
        if (isset($this->filters['start_time']))  $where[] = ['>=', 'created_at', strtotime($this->filters['start_time'])];
        if (isset($this->filters['end_time']))    $where[] = ['<=', 'created_at', strtotime($this->filters['end_time'] . ' 23:59:59')];
        if (isset($this->filters['user_card_status']) && $this->filters['user_card_status'] != 'All')   $where[] = ['user_card_status' => $this->filters['user_card_status']];
        if (isset($this->filters['status']) && $this->filters['status'] != 'All')                       $where[] = ['status' => $this->filters['status']];
        return $where;
    }

    /**
     * 查询对象
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = self::find();
        $query->filterWhere($this->queryCondition());
        return $query;
    }
}
