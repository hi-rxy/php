<?php

namespace backend\controllers;

use backend\models\search\CategorySearch;
use common\models\Category;
//use jinxing\admin\behaviors\Logging;
//use jinxing\admin\helpers\Helper;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii;

/**
 * Class CategoryController 文章分类 执行操作控制器
 * @package backend\controllers
 */
class CategoryController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Category';

    /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id';

    /**
     * @var CategorySearch string 定义使用的查询model
     */
    public $modelSearchClass = null;

    /**
     * @var string sort 定义默认排序字段名称
     */
    protected $sort = [
        'parent_id' => SORT_DESC,
        'sort' => SORT_ASC,
    ];

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
//     * 定义行为
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
//                        'children',
//                        'update-sort'
//                    ]
//                ]
//            ]
//        );
//    }

    /**
     * @param yii\base\Action $action
     * @return bool|string
     */
    public function beforeAction($action)
    {
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new CategorySearch();
        return parent::beforeAction($action);
    }

    /**
     * 查询条件
     * @param array|mixed $where
     * @return yii\db\ActiveQuery|yii\db\Query
     */
    protected function getQuery($where)
    {
        $filters = Yii::$app->request->get('filters', []);
        return $this->modelSearchClass->search($filters)->asArray();
    }

    /**
     * 文章分类
     * @return string
     */
    public function actionHome()
    {
        return $this->render('default',[
            'trees' => Category::getJsMenus()
        ]);
    }

    /**
     * 文章管理主页
     * @return string
     */
    public function actionIndex()
    {
        $classify = ['全部分类'];
        if ($class_id = Yii::$app->request->get('id',0))
        {
            $classify = array_reverse(Category::getClassName($class_id));
        }

        $item = Category::getCategoryData();

        return $this->render('index', [
            'options'  => $item['options'],
            'category' => Json::encode(ArrayHelper::map($item['category'], 'id', 'name')),
            'classify' => $classify,
        ]);
    }

    /**
     * 修改排序值
     * @return mixed|string
     */
    public function actionUpdateSort()
    {
        if (Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();

            try {
                /** @var $model Category */
                if (!$model = $this->findOne($post)) return $this->returnJson();

                $result = Category::doUpdateField($model,'sort',$post);
                if (is_object($result) && ($result instanceof Category)) return $this->success($result,'保存成功');
            } catch (\yii\db\Exception $exception) {
                return $this->error(201, $exception->getMessage());
            } catch (\yii\base\Exception $exception) {
                return $this->error(201, $exception->getMessage());
            }
        }

        return $this->returnJson();
    }

    /**
     * 显示添加子集FORM表单
     * @return mixed|string
     */
    public function actionChildren()
    {
        if (Yii::$app->request->isPost)
        {
            if (!$data = Yii::$app->request->post()) return $this->error(201);
            /* @var $model ActiveRecord */
            $model = new $this->modelClass();

            $data['parent_id'] = $data['id'];
            $result = Category::doCreate($model,$data);

            if (is_object($result) && ($result instanceof Category)) return $this->success($model,'保存成功');

            return $this->error(205,$result);
        }

        return $this->render('_form', [
            'options'  => Category::getCategoryData(Yii::$app->request->get('id', null))['options'],
        ]);
    }
}
