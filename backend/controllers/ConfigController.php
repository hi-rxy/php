<?php
namespace backend\controllers;

use backend\models\search\ConfigSearch;
//use jinxing\admin\behaviors\Logging;
use Yii;
use common\models\Config;
use yii\db\ActiveQuery;
use yii\db\Query;
//use yii\helpers\ArrayHelper;

/**
 * Class ConfigController 配置参数管理 执行操作控制器
 * @package backend\controllers
 */
class ConfigController extends Controller
{
    /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id'; 
    
    /**
     * @var array sort 定义默认排序字段名称
     */
    protected $sort = [
        'sort' => SORT_ASC
    ];
   
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Config';

    /**
     * @var ConfigSearch string 定义使用的查询model
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

    /**
     * 前置操作 当执行这些 'create','update','delete','delete-all'操作 删除redis缓存
     * @param $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->isPost && in_array($action->id,['create','update','delete','delete-all'])) {
            if (Yii::$app->cache->exists($this->redisKey[0])) {
                Yii::$app->cache->delete($this->redisKey[0]);
            }
        }
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new ConfigSearch();
        return parent::beforeAction($action);
    }

    /**
     * 查询条件
     * @param array|mixed $where
     * @return ActiveQuery|Query
     */
    protected function getQuery($where)
    {
        $filters = Yii::$app->request->get('filters', []);
        return $this->modelSearchClass->search($filters)->orderBy(['config_.id' => SORT_DESC])->asArray();
    }

    /**
     * 配置页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index',[
            'arrStatus'    => Config::getArrayStatus(),
            'arrColor'     => Config::getStatusColor(),
            'arrFormTypes' => Config::getShowTypes()
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
            $post = Yii::$app->request->post();
            try {
                $model = Config::findOne($post['id']);
                if (is_null($model)) throw new yii\base\Exception('参数错误');

                $func = function ($field,$value) {
                    switch ($field)
                    {
                        case 'sorts' :
                            $res = (int)$value;
                            break;
                        default :
                            $res = (int)$value ? Config::STATUS_CLOSE : Config::STATUS_OPEN;
                            break;
                    }
                    return $res;
                };

                $result = Config::doUpdateField($model,$func,$post);
                if (is_object($result) && $result instanceof Config) return $this->success($result,'保存成功');

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
