<?php
namespace backend\controllers;

use backend\models\search\GoodsClassSearch;
use common\models\GoodsClass;
//use jinxing\admin\behaviors\Logging;
use yii;
use common\models\GoodsType;
use jinxing\admin\helpers\Helper;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class GoodsClassController 商品分类管理 执行操作控制器
 * @package backend\controllers
 */
class GoodsClassController extends Controller
{
    /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id'; 
    
    /**
     * @var array sort 定义默认排序字段名称
     */
    protected $sort = [
        'sort' => SORT_ASC,
        'id' => SORT_ASC,
    ];
   
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\GoodsClass';

    /**
     * @var GoodsClassSearch string 定义使用的查询model
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
//                        'children',
//                        'update-single',
//                    ]
//                ]
//            ]
//        );
//    }

    public function beforeAction($action)
    {
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new GoodsClassSearch();
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
        return $this->modelSearchClass->search($filters)->with(['goodsType'])->asArray();
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
     * 商品分类
     * @return string
     */
    public function actionHome()
    {
        return $this->render('default',[
            'trees' => GoodsClass::getJsMenus()
        ]);
    }

    /**
     * 商品分类主页
     * @return string
     */
    public function actionIndex()
    {
        $classify = ['全部分类'];
        if ($class_id = Yii::$app->request->get('id',0)) {
            $classify = array_reverse(GoodsClass::getClassName($class_id));
        }

        $item = GoodsClass::getGoodsClassData();

        return $this->render('index', [
            'options'       => $item['options'],
            'classifyName'  => $classify,
            'arrStatusColor'=> GoodsClass::getStatusColor(),
            'arrStatus'     => GoodsClass::getArrayStatus(),
            'arrNavStatus'  => GoodsClass::getArrayStatus(1),
            'goodsType'     => Helper::map(GoodsType::queryCondition()->asArray()->all(),'id','name'),
            'goodsClass'    => ArrayHelper::map($item['goodsClass'], 'id', 'name'),
        ]);
    }

    /**
     * 重写添加方法
     * @return mixed|string
     */
    public function actionCreate()
    {
        if (!$data = Yii::$app->request->post()) return $this->error(201);

        /* @var $model ActiveRecord */
        $model = new $this->modelClass();

        try {
            // 计算级别 level 默认值 1
            if ($data['pid'])
            {
                /** @var GoodsClass $classify */
                $classify = GoodsClass::findOne(['id' => $data['pid']]);
                // 最多三层
                if ($classify->level <= 2)
                {
                    $data['level'] = (int)$classify->level + 1;
                }
            }

            $result = GoodsClass::doCreate($model,$data);
            if (is_object($result) && $result instanceof GoodsClass) {
                return $this->success($result,'保存成功');
            }
        } catch (yii\base\Exception $exception) {
            return $this->error(201,$exception->getMessage());
        }

        return $this->success($model);
    }

    /**
     * 修改排序值
     * @return mixed|string
     */
    public function actionUpdateSingle()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            try {
                $model = GoodsClass::findOne($post['id']);
                if (is_null($model)) throw new yii\base\Exception('参数错误');

                $func = function ($field,$value) {
                    switch ($field)
                    {
                        case 'sort' :
                            $res = (int)$value;
                            break;
                        default :
                            $res = (int)$value ? GoodsClass::STATUS_CLOSE : GoodsClass::STATUS_OPEN;
                            break;
                    }
                    return $res;
                };

                $result = GoodsClass::doUpdateField($model,$func,$post);
                if (is_object($result) && $result instanceof GoodsClass) return $this->success($result,'保存成功');

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
     * 添加子级分类
     * @return mixed|string
     */
    public function actionChildren()
    {
        if (Yii::$app->request->isPost)
        {
            try {
                if (!$data = Yii::$app->request->post()) throw new Exception('',201);

                /* @var $model ActiveRecord */
                $model = new $this->modelClass();

                /** @var GoodsClass $classify */
                $classify    = GoodsClass::findOne($data['id']);

                $level          = (int)$classify->level + (int)$data['level'];
                $data['pid']    = $data['id'];
                $data['level']  = $level;

                $result = GoodsClass::doCreate($model,$data);
                if (is_object($result) && $result instanceof GoodsClass) return $this->success($result,'保存成功');
            } catch (Exception $exception) {
                if (!$exception->getCode()) 
                {
                    $errStr = '';
                    if ($exception->getMessage()) {
                        $errStr = $exception->getMessage();
                    }
                    return $this->error($exception->getCode(),$errStr);
                }
                return $this->success('success');
            }
        }

        return $this->render('_form',[
            'goodsType' => ArrayHelper::map(GoodsType::queryCondition()->asArray()->all(),'id','name')
        ]);
    }

    /**
     * 获取子级分类
     * @return mixed|string
     */
    public function actionGetChildClassByGoods()
    {
        $post = Yii::$app->request->post();
        if (!count($post)) {
            return $this->returnJson();
        }

        $class_id = $post['class_id'];
        $goods_class_one = GoodsClass::findOne($class_id);
        if (is_null($goods_class_one)) {
            return $this->returnJson();
        }

        // 分类
        $class_data       = $this->modelSearchClass->search(['pid' => $class_id, 'status' => GoodsClass::STATUS_OPEN])->asArray()->all();

        // 分类下的属性和规格
        $class_type_attrs = $this->modelSearchClass->search(['id' => $class_id, 'status' => GoodsClass::STATUS_OPEN])->with('goodsType.attr')->asArray()->one();

        $attrs            = $class_type_attrs['goodsType']['attr'];
        if (!empty($attrs)) {
            foreach ($attrs as $key => $attr) {
                if ($attrs[$key]['status']) {// 判断属性状态
                    continue;
                }
                if (!empty($attrs[$key]['value'])) {
                    $attrs[$key]['value'] = explode(",", $attrs[$key]['value']);
                } else {
                    $attrs[$key]['value'] = array();
                }
                $arr[$attrs[$key]['style']][] = $attrs[$key];
            }
            $attrs = $arr;
        }

        //组装返回的数据
        $goods_attr_data = isset($attrs[0]) ? $attrs[0] : array();//0->参数
        $goods_spec_data = isset($attrs[1]) ? $attrs[1] : array();//1->规格

        return $this->success([
            'attrs' => $goods_attr_data,
            'specs' => $goods_spec_data,
            'class' => $class_data
        ]);
    }
}
