<?php
namespace backend\controllers;

use backend\models\search\StoreClassSearch;
use common\models\Store;
//use jinxing\admin\behaviors\Logging;
use Yii;
use common\models\StoreClass;
//use jinxing\admin\helpers\Helper;
use yii\helpers\ArrayHelper;

/**
 * Class StoreClassController 店铺分类 执行操作控制器
 * @package backend\controllers
 */
class StoreClassController extends Controller
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
    public $modelClass = 'common\models\StoreClass';

    /**
     * @var StoreClassSearch string 定义使用的查询model
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
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new StoreClassSearch();
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
        return $this->modelSearchClass->search($filters)->with(['store','admin','parents'])->asArray();
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
     * 店铺分类显示页面
     * @return string
     */
    public function actionIndex()
    {
        $classify = ['全部分类'];
        if ($class_id = Yii::$app->request->get('id',0)) {
            $classify = array_reverse(StoreClass::getClassName($class_id));
        }
        return $this->render('index',[
            'classifyName'  => $classify,
            'arrStatus' => StoreClass::getArrayStatus(),
            'arrStatusColor' => StoreClass::getStatusColor(),
            'arrNavStatus' => StoreClass::getArrayNavStatus(),
            'arrNavStatusColor' => StoreClass::getNavStatusColor(),
            'arrStore' => ArrayHelper::map(Store::find()->select(['id','name'])->asArray()->all(),'id','name'),
            'arrStoreClassParents' => [0 => '请选择']
        ]);
    }

    /**
     * 商品分类
     * @return string
     */
    public function actionHome()
    {
        return $this->render('default',[
            'trees' => StoreClass::getJsMenus()
        ]);
    }

    /**
     * 修改排序值
     * @return mixed|string
     */
    public function actionUpdateSingle()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            /** @var $model StoreClass */
            if (!$model = $this->findOne($post)) return $this->returnJson();

            // 字段值
            $func = function ($field,$value) {
                switch ($field)
                {
                    case 'sort' :
                        $res = (int)$value;
                        break;
                    default :
                        $res = (int)$value ? StoreClass::STATUS_CLOSE : StoreClass::STATUS_OPEN;
                        break;
                }
                return $res;
            };

            $result = StoreClass::doUpdateField($model,$func,$post);
            if (is_object($result) && $result instanceof StoreClass) return $this->success($result,'保存成功');

            return $this->success($model);
        }

        return $this->returnJson();
    }

    /**
     * 获取店铺分类的父级
     * @return mixed|string
     */
    public function actionGetParentsByStoreId()
    {
        $post = Yii::$app->request->post();
        if (!count($post)) return $this->returnJson();

        $store_id = (int)$post['store_id'];

        if ($store_id) {
            $store_class = $this->modelSearchClass->search(['pid' => 0, 'status' => StoreClass::STATUS_OPEN, 'store_id' => $store_id])->asArray()->all();

            if ($store_class) {
                $html = '<option value="0">请选择</option>';
                foreach ($store_class as $item) {
                    $selected = '';
                    if ($post['pid'] == $item['id']){
                        $selected = "selected";
                    }
                    $html .= '<option '.$selected.' value="' . $item['id'] . '">' . $item['name'] . '</option>';
                }
            }
        }

        return $this->success(isset($html) ? $html : '');
    }

    /**
     * 获取店铺分类子级
     * @return mixed|string
     */
    public function actionGetChildClassByStore()
    {
        $post           = Yii::$app->request->post();
        if (!count($post)) return $this->returnJson();

        $class_id       = (int)$post['class_id'];
        $storeClassOne  = StoreClass::findOne($class_id);
        if (is_null($storeClassOne)) return $this->returnJson();

        $storeClass = $this->modelSearchClass->search(['pid' => $class_id, 'status' => StoreClass::STATUS_OPEN])->asArray()->all();
        
        return $this->success(['class' => $storeClass]);
    }
}
