<?php

namespace api\traits;

trait Search
{
    private $actionFields = "getField";
    private $actionWhere = "getWhere";
    private $actionSort = "getSort";
    private $actionQuery = "getQuery";
    private $actionJoin = "getJoin";
    private $actionAfterSearch = "afterSearch";

    /**
     * @Notes: 字段
     * @Function getWhere
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:09
     */
    protected function getField ()
    {
        return [];
    }

    /**
     * @Notes: 条件
     * @Function getWhere
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:09
     */
    protected function getWhere ()
    {
        return [];
    }

    /**
     * @Notes: 排序
     * @Function sorts
     * @return string
     * @author: Admin
     * @Time: 2022/2/22 13:09
     */
    protected function getSort ()
    {
        $rule = [ '+' => 'asc', '-' => 'desc' ];
        $sort = \Yii::$app->request->get('sort', '+id');
        $column = substr($sort, 1);
        $symbol = substr($sort, 0, 1);
        $params = '';
        foreach ($rule as $key => $value) {
            if ($key == $symbol) {
                $params .= "$column $value";
            }
        }
        return $params;
    }

    /**
     * @Notes: 关联
     * @Function: getJoin
     * @return array
     * @Author: 17908
     * @Time: 2022/2/24 0024 23:01
     */
    protected function getJoin ()
    {
        list($exits,$type,$table,$on,$params) = [];
        return [compact('exits','type','table','on','params')];
    }

    /**
     * @Notes: 查询后的数据处理
     * @Function afterSearch
     * @param $array
     * @author: Admin
     * @Time: 2022/2/22 13:09
     */
    protected function afterSearch ( &$array = [] )
    {

    }

    /**
     * @Notes: 查询对象
     * @Function getQuery
     * @return \yii\db\Query
     * @author: Admin
     * @Time: 2022/2/22 13:10
     */
    protected function getQuery (  )
    {
        $select = call_user_func([$this,$this->actionFields]);
        $where = call_user_func([ $this, $this->actionWhere ]);
        $join = call_user_func([ $this, $this->actionJoin ]);
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->modelClass;
        $query =  ( new \yii\db\Query() )->select($select)->from(['_yii2' => $model::tableName()])->where($where);
        if ($join['exits']) $query->join($join['type'],$join['table'],$join['on'],$join['params']);
        return $query;
    }

    /**
     * @Notes: 查询方法
     * @Function actionSearch
     * @return array
     * @throws \Exception
     * @author: Admin
     * @Time: 2022/2/22 13:10
     */
    public function actionSearch ()
    {
        $array = [];
        $page = $this->request->get('page', 1);
        $rows = $this->request->get('limit', 10);
        $offset = ( $page - 1 ) * $rows;
        /** @var \yii\db\Query $query */
        $query = call_user_func([ $this, $this->actionQuery ]);
        $total = $query->count();
        if ($total) {
            $order = call_user_func([ $this, $this->actionSort ]);
            $obj = $query->offset($offset)->limit($rows)->orderBy($order);
            //echo $obj->createCommand()->getRawSql();die();
            $array = $obj->all();
            if (method_exists($this, $this->actionAfterSearch)) {
                $method = $this->actionAfterSearch;
                $this->$method($array);
            }
        }
        $params['items'] = $array;
        $params['total'] = (int)$total;// 总数量
        $params['pageCount'] = (int)ceil(( $total / $rows ));// 总页数
        $params['pageSize'] = (int)$page;
        return $this->success($params);
    }
}
