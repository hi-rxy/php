<?php
namespace backend\controllers;

use backend\models\search\CommentSearch;
use common\models\Article;
use common\models\Comment;
//use jinxing\admin\behaviors\Logging;
//use jinxing\admin\helpers\Helper;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
//use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/**
 * Class CommentController 文章评论 执行操作控制器
 * @package backend\controllers
 */
class CommentController extends Controller
{
        /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id'; 
    
        /**
     * @var string sort 定义默认排序字段名称
     */
    protected $sort = 'id'; 
   
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Comment';

    /**
     * @var CommentSearch string 定义使用的查询model
     */
    public $modelSearchClass = null;
    
    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
     * 
     * @return array 
     */
    public function where()
    {
        return [];
    }

//    /**
//     * 行为
//     *
//     * @return array
//     */
//    public function behaviors()
//    {
//        return ArrayHelper::merge(
//            parent::behaviors(),
//            [
//                'logging' => [
//                    'class' => Logging::className(),
//                    'needLogActions' => [
//                        'create',
//                        'update',
//                        'delete',
//                        'delete-all',
//                        'editable',
//                        'upload',
//                    ]
//                ]
//            ]
//        );
//    }

    public function beforeAction($action)
    {
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new CommentSearch();
        return parent::beforeAction($action);
    }

    /**
     * 查询条件
     * @param array|mixed $where
     * @return yii\db\ActiveQuery|yii\db\Query
     */
    protected function getQuery($where)
    {
        $get     = Yii::$app->request->get('filters', []);
        $post    = Yii::$app->request->post('filters', []);
        $filters = array_merge($get,$post);
        return $this->modelSearchClass->search($filters)->joinWith(['article'])->asArray();
    }

    /**
     * 处理查询后的数组
     * @param mixed $array
     */
    public function afterSearch(&$array)
    {
        return $this->modelSearchClass->afterSearch($array);
    }

    /**
     * 文章评论页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index',[
            'arrStatus' => Comment::getArrayStatus(),
            'arrColorStatus' => Comment::getStatusColor(),
        ]);
    }

    /**
     * 评论列表
     * @return mixed|string
     */
    public function actionList ()
    {
        $articleId  = (int)(Yii::$app->request->get('articleId',0));

        $count      = $this->modelSearchClass->search(['article_id' => $articleId])->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 6]);

        $data       = $this->modelSearchClass
            ->search(['article_id' => $articleId])
            ->select(['id','admin_id','nickname','created_at','content','reply_to'])
            ->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $pageStr = LinkPager::widget([
            'pagination' => $pagination
        ]);

        $pageStr = preg_replace('/href="[^"]+page=(\d+)[^"]+"/', 'onclick="ajaxData(this,\1);"', $pageStr);

        return $this->success([
            'count' => $count,
            'link'  => $pageStr,
            'data'  => Comment::handleCommentData($data)
        ]);
    }

    /**
     * 文章评论
     * @return string
     */
    public function actionCreate ()
    {
        if (Yii::$app->request->isPost)
        {
            $articleId  = (int)Yii::$app->request->post('articleId',0);
            $commentId  = (int)Yii::$app->request->post('comment_id',0);
            $nickname   = trim(Yii::$app->request->post('name',''));
            $content    = trim(Html::encode(Yii::$app->request->post('content','')));

            try {
                $result = Comment::doCreate((new $this->modelClass),[
                    'articleId' => $articleId,
                    'comment_id' => $commentId,
                    'name' => $nickname,
                    'content' => $content,
                ]);
                if (is_object($result) && $result instanceof Comment) return $this->success($result,'保存成功');
            } catch (Exception $exception) {
                return $this->error(205,$exception->getMessage());
            }
        }

        return $this->render('_form', [
            'model' => Article::findOne(Yii::$app->request->get('id', 0))
        ]);
    }
}
