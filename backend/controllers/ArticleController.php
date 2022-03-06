<?php

namespace backend\controllers;

use backend\models\search\ArticleSearch;
use common\models\Article;
use common\models\ArticleContent;
use common\models\Category;
//use jinxing\admin\behaviors\Logging;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;


/**
 * Class ArticleController 文章管理 执行操作控制器
 * @package backend\controllers
 */
class ArticleController extends Controller
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
    public $modelClass = 'common\models\Article';

    /**
     * @var ArticleSearch string 定义使用的查询model
     */
    public $modelSearchClass = null;

    /**
     * @var string 定义上传文件使用的model
     */
    public $uploadFromClass = 'backend\models\forms\UploadForm';

    /**
     * @var string 定义上传文件的目录
     */
    public $strUploadPath = './uploads/article/';

    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
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
//                        'update-single'
//                    ]
//                ]
//            ]
//        );
//    }

    /**
     * @return array[]
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'cliff363825\kindeditor\KindEditorUploadAction',
                'basePath' => '@frontend/web/uploads/kindEditor/article', //图片保存的物理路径
                'baseUrl' => Yii::$app->params['site']['UPLOAD_PATH'].'kindEditor/article',//图片的url
                'maxSize' => 2097152, //图片的限制
            ],
        ];
    }

    /**
     * @param yii\base\Action $action
     * @return bool|string
     */
    public function beforeAction($action)
    {
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new ArticleSearch();
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
        return $this->modelSearchClass->search($filters)->joinWith(['category'])->asArray();
    }

    /**
     * 文章管理页面
     * @return string
     */
    public function actionIndex()
    {
        $item = Category::getCategoryData();

        return $this->render('index', [
            'options'       => $item['options'],
            'category'      => Json::encode(ArrayHelper::map($item['category'], 'id', 'name')),
            'aStatus'       => Article::getArrayStatus(),
            'aBoolStatus'   => Article::getBoolStatus(),
            'aColorStatus'  => Article::getStatusColor(),
        ]);
    }

    /**
     * 查看文章
     * @return string
     */
    public function actionView()
    {
        return $this->render('view', [
           'model' => Article::getInstance(Yii::$app->request->get('id', 0))
        ]);
    }

    /**
     * 重写添加方法
     * @return mixed|string
     */
    public function actionCreate()
    {
        $model = new $this->modelClass;

        if (Yii::$app->request->isPost)
        {
            $request = Yii::$app->request;
            $post    = $request->post();

            try {
                $result = Article::doCreate($model,Article::formatData($post));
                if (is_object($result) && $result instanceof Article) {
                    return $this->success($result,'保存成功');
                }
            } catch (yii\base\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            }
        }

        return $this->render('_form', [
            'model'     => $model,
            'options'   => Category::getCategoryData()['options']
        ]);
    }

    /**
     * 重写修改方法
     * @return mixed|string
     */
    public function actionUpdate()
    {
        /** @var Article $model */
        $model = Article::findOne(Yii::$app->request->get('id', 0));

        if (Yii::$app->request->isPost)
        {
            $request = Yii::$app->request;
            $post    = $request->post();

            try {
                $result = Article::doUpdate($model,Article::formatData(array_merge($post,['id' => $model->id])));
                if (is_object($result) && $result instanceof Article) {
                    return $this->success($result,'保存成功');
                }
            } catch (yii\base\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            }
        }

        return $this->render('_form', [
            'model' => $model,
            'options' => Category::getCategoryData($model->category_id)['options']
        ]);
    }

    /**
     * 修改单个值
     * @return mixed|string
     */
    public function actionUpdateSingle()
    {
        if (Yii::$app->request->isPost)
        {
            $post  = Yii::$app->request->post();
            try {
                $model = Article::findOne($post['id']);
                if (is_null($model)) throw new yii\base\Exception('参数错误');

                $func = function ($field,$value) {
                    switch ($field)
                    {
                        case 'sorts' :
                            $res = (int)$value;
                            break;
                        default :
                            $res = (int)$value ? Article::STATUS_NO : Article::STATUS_YES;
                            break;
                    }
                    return $res;
                };

                $result = Article::doUpdateField($model,$func,$post);
                if (is_object($result) && $result instanceof Article) return $this->success($result,'保存成功');

                return $this->error(201,'更新失败');
            } catch (\yii\db\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            } catch (\yii\base\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            }
        }

        return $this->returnJson();
    }

    /**
     * 批量删除之后的方法
     * @return mixed|string
     */
    protected function afterDeleteAll()
    {
        // 删除文章内容
        return ArticleContent::deleteAll(['article_id' => explode(',',Yii::$app->request->post($this->pk))]);
    }
}
