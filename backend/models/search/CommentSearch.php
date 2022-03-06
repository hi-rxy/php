<?php
namespace backend\models\search;

use common\models\Comment;

/**
 * 评论搜索模型
 * @package backend\models
 */
class CommentSearch extends Comment
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
        if (isset($this->filters['article_title'])) {
            $where[] = ['like', 'article_.title', $this->filters['article_title']];
        }
        if (isset($this->filters['article_id'])) {
            $where[] = ['comment_.article_id' => $this->filters['article_id']];
        }
        if (isset($this->filters['start_time'])) {
            $where[] = ['>=', 'comment_.created_at', strtotime($this->filters['start_time'])];
        } else {
            //$where[] = ['>=', 'comment_.created_at', getStartDayByMonth()];
        }
        if (isset($this->filters['end_time'])) {
            $where[] = ['<=', 'comment_.created_at', strtotime($this->filters['end_time'] . ' 23:59:59')];
        } else {
            //$where[] = ['<=', 'comment_.created_at', strtotime(date('Y-m-d', getEndDayByMonth()) . ' 23:59:59')];
        }
        return $where;
    }

    /**
     * 查询对象
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = Comment::find();
        $query->alias('comment_');
        $query->filterWhere($this->queryCondition());
        return $query;
    }

    /**
     * 查询后的数据修改
     * @param $array
     * @return mixed
     */
    public function afterSearch (&$array)
    {
        foreach ($array as &$value) {
            $value['article_title'] = $value['article']['title'];
            unset($value['article']);
        }
        unset($value);
        return $array;
    }
}
