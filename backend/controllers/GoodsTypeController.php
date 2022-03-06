<?php

namespace backend\controllers;

use backend\models\search\GoodsTypeSearch;
use common\models\Attr;
use common\models\GoodsType;
//use jinxing\admin\behaviors\Logging;
//use jinxing\admin\helpers\Helper;
use yii;
//use yii\helpers\ArrayHelper;

/**
 * Class GoodsTypeController 商品类型管理 执行操作控制器
 * @package backend\controllers
 */
class GoodsTypeController extends Controller
{
    /** @var string pk 定义表使用的主键名称 */
    protected $pk = 'id';

    /**
     * @var string sort 定义默认排序字段名称
     */
    protected $sort = 'id';

    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\GoodsType';

    /**
     * @var GoodsTypeSearch string 定义使用的查询model
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
//                        'update-single'
//                    ]
//                ]
//            ]
//        );
//    }

    public function beforeAction($action)
    {
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new GoodsTypeSearch();
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
        return $this->modelSearchClass->search($filters)->with(['attr'])->asArray();
    }

    /**
     * 查询后数据处理
     * @param mixed $array
     */
    protected function afterSearch(&$array)
    {
        return $this->modelSearchClass->afterSearch($array);
    }

    /**
     * 删除类型属性
     */
    protected function afterDeleteAll()
    {
        return Attr::deleteAll([
            'type_id' => explode(',', Yii::$app->request->post('id'))
        ]);
    }

    /**
     * 商品类型页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'arrGoodsTypeStatus'    => GoodsType::getArrayStatus(),
            'arrGoodsTypeColor'     => GoodsType::getStatusColor()
        ]);
    }

    /**
     * 修改单个值
     * @return mixed|string
     */
    public function actionUpdateSingle()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            try {
                $model = GoodsType::findOne($post['id']);
                if (is_null($model)) throw new yii\base\Exception('参数错误');

                // 更新字段
                $func = function ($name,$value) {
                    return (int)$value ? GoodsType::STATUS_CLOSE : GoodsType::STATUS_OPEN;
                };

                $result = GoodsType::doUpdateField($model,$func,$post);
                if (is_object($result) && $result instanceof GoodsType) return $this->success($result);

                return $this->error(201,'更新失败');
            } catch (\yii\db\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            } catch (\yii\base\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            }
        }

        return $this->returnJson();
    }
}
