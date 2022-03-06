<?php
namespace backend\models\search;

use common\models\Article;
use common\models\Category;

/**
 * 文章搜索模型
 * @package backend\models
 */
class ArticleSearch extends Article
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

    private function getCategoryIds ($category_id)
    {
        $model = Category::findOne($category_id);
        if ((int)$model->parent_id) { // 二级分类
            $category_ids = $model->id;
        } else { // 一级分类
            $category_ids = Category::find()->select(['id'])->where([
                'or',
                ['parent_id' => $category_id],
                ['id' => $category_id]
            ])->column();
        }
        return $category_ids;
    }

    /**
     * 查询条件
     * @param $filters
     * @return string[]
     */
    private function queryCondition ()
    {
        $where = ['and'];
        if (isset($this->filters['category_id']) && $this->filters['category_id'] != 'ALL' && $this->filters['category_id'] > 0) {
            $where[] = ['article_.category_id' => $this->getCategoryIds($this->filters['category_id'])];
        }
        if (isset($this->filters['title']))       $where[] = ['like', 'article_.title', $this->filters['title']];
        if (isset($this->filters['start_time']))  $where[] = ['>=', 'article_.updated_at', strtotime($this->filters['start_time'])];
        if (isset($this->filters['end_time']))    $where[] = ['<=', 'article_.updated_at', strtotime($this->filters['end_time'] . ' 23:59:59')];

        return $where;
    }

    /**
     * 查询对象
     * @param $filters
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = self::find();
        $query->alias('article_');
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
        foreach ($array as &$value)
        {
            unset($value['category']);
        }
        return $array;
    }
}
